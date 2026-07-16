<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Organization;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_checkout_tickets_and_create_order(): void
    {
        $customer = User::factory()->create();
        
        $org = Organization::factory()->create(['status' => 'approved', 'commission_rate' => 5.0]);
        $event = Event::factory()->create(['organization_id' => $org->id, 'status' => 'published']);
        
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'price' => 100000,
            'quantity' => 100,
            'is_active' => true
        ]);

        $this->withoutExceptionHandling();
        $response = $this->actingAs($customer)->post(route('checkout.store', $event), [
            'tickets' => [
                $ticketType->id => 2 // 2 tickets
            ],
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '08123456789'
        ]);

        // Should redirect to Midtrans payment URL or show success/payment page
        // Since Midtrans is mocked or might throw if not configured, let's see. 
        // If Midtrans is not configured, it might return 500 in test.
        // Let's assert the database has the order.
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'total_amount' => 205000, // 2 * 100000 + 5000 fee
            'status' => 'pending'
        ]);
        
        $this->assertDatabaseHas('order_items', [
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2,
            'unit_price' => 100000
        ]);
    }
}
