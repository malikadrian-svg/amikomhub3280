<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Request $request, Event $event)
    {
        $categories = \App\Models\Category::all();

        // 1. Process selected tickets
        $selectedTicketsRaw = $request->input('tickets', []);
        $selectedTickets = [];
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($selectedTicketsRaw as $ticketId => $qty) {
            $qty = (int)$qty;
            if ($qty > 0) {
                $ticket = \App\Models\TicketType::find($ticketId);
                // Ensure ticket exists, belongs to this event, and has stock
                if ($ticket && $ticket->event_id === $event->id) {
                    if ($qty > $ticket->remaining()) {
                        return redirect()->route('events.show', $event)->with('error', "Stok tiket '{$ticket->name}' tidak mencukupi untuk jumlah yang diminta.");
                    }
                    if ($qty > $ticket->max_per_order) {
                        return redirect()->route('events.show', $event)->with('error', "Maksimal pemesanan untuk tiket '{$ticket->name}' adalah {$ticket->max_per_order}.");
                    }
                    
                    $subtotal += ($ticket->price * $qty);
                    $totalQuantity += $qty;
                    $selectedTickets[] = [
                        'ticket' => $ticket,
                        'qty' => $qty,
                        'subtotal' => $ticket->price * $qty,
                    ];
                }
            }
        }

        if (empty($selectedTickets)) {
            return redirect()->route('events.show', $event)->with('error', 'Silakan pilih setidaknya satu tiket untuk melanjutkan.');
        }

        // Dummy platform fee calculation
        $platformFee = 5000;
        $totalAmount = $subtotal + $platformFee;

        // Pass the authenticated user so the view can pre-fill the form
        $authUser = Auth::user();

        return view('checkout.create', compact('event', 'categories', 'authUser', 'selectedTickets', 'subtotal', 'platformFee', 'totalAmount'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validate Customer Input and Tickets
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'tickets' => 'required|array',
        ]);

        $selectedTicketsRaw = $request->input('tickets', []);
        $orderItemsData = [];
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($selectedTicketsRaw as $ticketId => $qty) {
            $qty = (int)$qty;
            if ($qty > 0) {
                $ticket = \App\Models\TicketType::find($ticketId);
                
                if (!$ticket || $ticket->event_id !== $event->id) {
                    return back()->with('error', 'Jenis tiket tidak valid.');
                }
                
                // Concurrency Note: In a highly scalable app we'd use pessimistic locking here
                // DB::beginTransaction() -> lockForUpdate()
                if ($qty > $ticket->remaining()) {
                    return back()->with('error', "Stok tiket '{$ticket->name}' tidak mencukupi. Sisa: " . $ticket->remaining());
                }

                $subtotal += ($ticket->price * $qty);
                $totalQuantity += $qty;
                
                $orderItemsData[] = [
                    'ticket_type_id' => $ticket->id,
                    'quantity'       => $qty,
                    'unit_price'     => $ticket->price,
                    'subtotal'       => $ticket->price * $qty,
                ];
            }
        }

        if ($totalQuantity === 0) {
            return back()->with('error', 'Pilih setidaknya 1 tiket.');
        }

        // 2. Calculate Totals
        $platformFee = 5000;
        $totalAmount = $subtotal + $platformFee;
        $orderNumber = 'ORD-' . strtoupper(Str::random(8)) . '-' . time();

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // 3. Create Order
            $order = \App\Models\Order::create([
                'organization_id' => $event->organization_id,
                'user_id'         => Auth::id(),
                'event_id'        => $event->id,
                'order_number'    => $orderNumber,
                'customer_name'   => $request->customer_name,
                'customer_email'  => $request->customer_email,
                'customer_phone'  => $request->customer_phone,
                'subtotal'        => $subtotal,
                'platform_fee'    => $platformFee,
                'total_amount'    => $totalAmount,
                'status'          => 'pending',
                'expired_at'      => now()->addHours(24),
            ]);

            // 4. Create Order Items
            foreach ($orderItemsData as $item) {
                $order->items()->create($item);
            }

            // 5. Create Transaction (to bridge with Midtrans)
            $trxId = 'TRX-' . time() . '-' . Str::random(5);
            $transaction = Transaction::create([
                'order_id'         => $order->id,
                'gateway_order_id' => $trxId,
                'payment_gateway'  => 'midtrans',
                'amount'           => $totalAmount,
                'status'           => 'pending',
            ]);

            // 6. Midtrans Integration
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
            
            $params = [
                'transaction_details' => [
                    'order_id' => $trxId,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                ],
            ];
            
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            \Illuminate\Support\Facades\DB::commit();
            
            // Fire OrderCreated Event for Notifications
            event(new \App\Events\OrderCreated($order));

            return redirect()->route('checkout.payment', $transaction->gateway_order_id);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
    
    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('order.event')->where('gateway_order_id', $order_id)->firstOrFail();
        
        return view('checkout.payment', compact('transaction', 'categories'));
    }
    
    public function success($order_id)
    {
        $categories = \App\Models\Category::all();
        // $order_id here refers to the Transaction's gateway_order_id (e.g., TRX-...)
        $transaction = Transaction::with(['order.event', 'order.items.ticketType'])
            ->where('gateway_order_id', $order_id)
            ->firstOrFail();

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        try {
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                if (in_array($trx_status, ['settlement', 'capture'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        \Illuminate\Support\Facades\DB::beginTransaction();
                        try {
                            // 1. Update Transaction
                            $transaction->update(['status' => 'success']);

                            // 2. Update Order
                            $order = $transaction->order;
                            if ($order && $order->status === 'pending') {
                                $order->update([
                                    'status' => 'paid',
                                    'paid_at' => now(),
                                ]);

                                // 3. Create Commission
                                // Use Organization override if exists, else Platform default
                                $ratePercentage = $order->organization->commission_rate 
                                    ?? \App\Models\PlatformSetting::get('default_commission_rate', 5.00);
                                    
                                $commissionRate = $ratePercentage / 100;
                                $commissionAmount = (int) ($order->subtotal * $commissionRate);
                                $organizerAmount = $order->subtotal - $commissionAmount;

                                \App\Models\OrderCommission::create([
                                    'order_id'          => $order->id,
                                    'organization_id'   => $order->organization_id,
                                    'gross_amount'      => $order->subtotal, // excluding the flat platform fee
                                    'commission_rate'   => $commissionRate,
                                    'commission_amount' => $commissionAmount,
                                    'organizer_amount'  => $organizerAmount,
                                    'settlement_status' => 'pending',
                                ]);

                                // 4. Generate Tickets & Reduce Stock
                                foreach ($order->items as $item) {
                                    $ticketType = $item->ticketType;
                                    
                                    // Reduce stock (quantity_sold)
                                    if ($ticketType) {
                                        $ticketType->increment('quantity_sold', $item->quantity);
                                    }

                                    // Generate individual tickets
                                    for ($i = 0; $i < $item->quantity; $i++) {
                                        $ticketCode = 'TIX-' . strtoupper(Str::random(10));
                                        
                                        \App\Models\Ticket::create([
                                            'order_item_id'  => $item->id,
                                            'user_id'        => $order->user_id,
                                            'event_id'       => $order->event_id,
                                            'ticket_type_id' => $item->ticket_type_id,
                                            'ticket_code'    => $ticketCode,
                                            // Optional: Generate an actual QR string later
                                            'qr_code'        => $ticketCode, 
                                            'status'         => 'active',
                                        ]);
                                    }
                                }

                                // 5. Fire OrderPaid Event (Handles Email & WhatsApp Notifications via Listeners)
                                event(new \App\Events\OrderPaid($order));
                            }
                            \Illuminate\Support\Facades\DB::commit();
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\DB::rollBack();
                            \Log::error('Error processing successful transaction: ' . $e->getMessage());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }
        
        return view('checkout.success', compact('transaction', 'categories'));
    }
}