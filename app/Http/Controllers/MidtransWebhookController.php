<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Mencari ID transaksi tersebut di database lokal kita
        $transaction = Transaction::with(['order.event', 'order.items.ticketType'])
            ->where('gateway_order_id', $orderId)
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang jika status sudah lunas/sukses
        if ($transaction->status === 'settlement' || $transaction->status === 'success') {
            return response()->json(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'settlement';
            $this->processSuccess($transaction);
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();

        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $order = $transaction->order;
            
            if ($order && $order->status === 'pending') {
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Create Commission
                $ratePercentage = $order->organization->commission_rate 
                    ?? \App\Models\PlatformSetting::get('default_commission_rate', 5.00);
                    
                $commissionRate = $ratePercentage / 100;
                $commissionAmount = (int) ($order->subtotal * $commissionRate);
                $organizerAmount = $order->subtotal - $commissionAmount;

                \App\Models\OrderCommission::create([
                    'order_id'          => $order->id,
                    'organization_id'   => $order->organization_id,
                    'gross_amount'      => $order->subtotal,
                    'commission_rate'   => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'organizer_amount'  => $organizerAmount,
                    'settlement_status' => 'pending',
                ]);

                // Generate Tickets & Reduce Stock
                foreach ($order->items as $item) {
                    $ticketType = $item->ticketType;
                    
                    if ($ticketType) {
                        $ticketType->increment('quantity_sold', $item->quantity);
                    }

                    for ($i = 0; $i < $item->quantity; $i++) {
                        $ticketCode = 'TIX-' . strtoupper(\Illuminate\Support\Str::random(10));
                        
                        \App\Models\Ticket::create([
                            'order_item_id'  => $item->id,
                            'user_id'        => $order->user_id,
                            'event_id'       => $order->event_id,
                            'ticket_type_id' => $item->ticket_type_id,
                            'ticket_code'    => $ticketCode,
                            'qr_code'        => \Illuminate\Support\Str::random(40), 
                            'status'         => 'active',
                        ]);
                    }
                }

                // Send Email
                try {
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)
                        ->send(new \App\Mail\EventTicketMail($order));
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim email E-Ticket dari Webhook: ' . $e->getMessage());
                }

                // Send Notification to Organizer
                if ($order->organization && $order->organization->owner) {
                    $order->organization->owner->notify(new \App\Notifications\OrderPaidNotification($order));
                }
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Log::error('Error in Webhook processSuccess: ' . $e->getMessage());
        }
    }
}