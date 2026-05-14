<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OcSolicitudMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $solicitud,
        public $tipo = 'created' // 'created', 'accepted', 'rejected'
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'created' => "APROBACIÓN REQUERIDA: Nueva Solicitud OC - {$this->solicitud->tipo_solicitud} (Monto > \$1M)",
            'info' => "Nueva Solicitud OC Registrada - {$this->solicitud->tipo_solicitud}",
            'accepted' => "Solicitud OC Aceptada - {$this->solicitud->tipo_solicitud}",
            'rejected' => "Solicitud OC Rechazada - {$this->solicitud->tipo_solicitud}",
        ];

        return new Envelope(
            subject: $subjects[$this->tipo] ?? 'Solicitud OC',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.oc-solicitud',
            with: [
                'solicitud' => $this->solicitud,
                'tipo' => $this->tipo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
