<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $order->load(['event', 'items.ticketType', 'items.tickets']);
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Resmi Anda: ' . $this->order->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            // Menentukan lokasi view HTML email di folder resources/views/email/...
            view: 'email.ticket',
        );
    }
}