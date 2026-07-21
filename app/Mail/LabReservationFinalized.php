<?php

namespace App\Mail;

use App\Models\LabReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LabReservationFinalized extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LabReservation $reservation,
        public string $recipient = 'professor'
    ) {}

    public function envelope(): Envelope
    {
        $coordenador = $this->reservation->coordenador;

        return new Envelope(
            subject: "Atividade validada — Reserva #{$this->reservation->id} | Etec SAM",
            replyTo: $coordenador?->email ? [new Address($coordenador->email, $coordenador->name)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lab-reservation-finalized',
            with: [
                'reservation' => $this->reservation,
                'recipient'   => $this->recipient,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
