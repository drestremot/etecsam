<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class PushNotificationService
{
    /**
     * Envia uma notificação push para todos os dispositivos registrados do usuário.
     * Remove tokens inválidos/expirados automaticamente.
     *
     * Resolve o Messaging do Firebase sob demanda (em vez de injetar no construtor)
     * para que a ausência de credenciais configuradas não quebre nenhuma outra
     * funcionalidade — só a notificação falha silenciosamente, como já ocorre
     * com falhas de e-mail em validateActivity().
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): void
    {
        $tokens = $user->deviceTokens()->pluck('token', 'id');

        if ($tokens->isEmpty()) {
            return;
        }

        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData($data);

        try {
            $report = app(Messaging::class)->sendMulticast($message, $tokens->values()->all());

            foreach ($report->invalidTokens() as $invalidToken) {
                $user->deviceTokens()->where('token', $invalidToken)->delete();
            }
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar push notification', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envia para vários usuários de uma vez, ignorando duplicados.
     *
     * @param  iterable<User>  $users
     */
    public function sendToUsers(iterable $users, string $title, string $body, array $data = []): void
    {
        $seen = [];
        foreach ($users as $user) {
            if (!$user || isset($seen[$user->id])) {
                continue;
            }
            $seen[$user->id] = true;
            $this->sendToUser($user, $title, $body, $data);
        }
    }
}
