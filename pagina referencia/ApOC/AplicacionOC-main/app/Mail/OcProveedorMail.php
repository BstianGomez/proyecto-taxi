<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OcProveedorMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $ocData
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Orden de Compra #{$this->ocData->numero_oc} - Fundación SOFOFA",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.oc-proveedor',
            with: [
                'oc' => $this->ocData,
                'comentario' => $this->ocData->comentario,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->ocData->file_path && Storage::disk('private')->exists($this->ocData->file_path)) {
            $attachments[] = Attachment::fromStorageDisk('private', $this->ocData->file_path)
                ->as("OC_{$this->ocData->numero_oc}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
