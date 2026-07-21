<?php

namespace App\Services;

use App\Mail\ReservationStepNotification;
use App\Models\LabReservation;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationNotifier
{
    public function __construct(private PushNotificationService $push)
    {
    }

    /**
     * Notifica os destinatários por push e por e-mail (assinado pelo sistema,
     * com "Responder para" apontando para quem executou a ação).
     *
     * @param  iterable<User|null>  $recipients
     */
    public function notify(iterable $recipients, LabReservation $reservation, string $title, string $message, User $actingUser): void
    {
        $this->push->sendToUsers($recipients, $title, $message, [
            'type'           => 'reservation_update',
            'reservation_id' => (string) $reservation->id,
        ]);

        $seen = [];
        foreach ($recipients as $user) {
            if (!$user || isset($seen[$user->id]) || !$user->email) {
                continue;
            }
            $seen[$user->id] = true;

            try {
                Mail::to($user->email)
                    ->send(new ReservationStepNotification($reservation, $title, $message, $actingUser));
            } catch (\Throwable $e) {
                Log::warning('Falha ao enviar e-mail de notificação de reserva', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }
}
