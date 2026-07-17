<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // 1. Tiêu đề Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Đơn hàng mới ' . $this->order->code,
        );
    }

    // 2. Nội dung Email
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_placed',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->order->payment_proof) {
            $filePath = storage_path('app/public/' . $this->order->payment_proof);

            if (file_exists($filePath)) {
                $attachments[] = Attachment::fromPath($filePath)
                    ->as('bang-chung-thanh-toan.jpg')
                    ->withMime('image/jpeg');
            }
        }

        return $attachments;
    }
}