<?php

namespace App\Mail;

use App\Models\LabReservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStepNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LabReservation $reservation,
        public string $title,
        public string $bodyText,
        public User $actingUser,
        public ?User $recipientUser = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->title} — Reserva #{$this->reservation->id} | Etec SAM",
            replyTo: $this->actingUser->email
                ? [new Address($this->actingUser->email, $this->actingUser->name)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-step-notification',
            with: [
                'reservation'   => $this->reservation,
                'title'         => $this->title,
                'bodyText'      => $this->bodyText,
                'actingUser'    => $this->actingUser,
                'recipientName' => $this->recipientUser?->name,
            ],
        );
    }
}
