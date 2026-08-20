<?php

namespace App\Services;

use App\Mail\SgiAccountRequestNotification;
use App\Models\SgiAccountRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SgiAccountRequestCommunicationService
{
    public function sendNewRequestNotifications(SgiAccountRequest $request): void
    {
        $this->sendClientConfirmation($request);
        $this->sendAdminNotification($request);
    }

    public function sendClientConfirmation(SgiAccountRequest $request): bool
    {
        if ($request->client_confirmation_sent_at) {
            return false;
        }

        return $this->sendAndMark(
            $request,
            $request->email,
            'client_confirmation',
            'client_confirmation_sent_at'
        );
    }

    public function sendAdminNotification(SgiAccountRequest $request): bool
    {
        if ($request->admin_notified_at) {
            return false;
        }

        return $this->sendAndMark(
            $request,
            $this->adminAddress(),
            'admin_new',
            'admin_notified_at'
        );
    }

    public function sendReminder(SgiAccountRequest $request): array
    {
        if ($request->status !== 'pending') {
            return ['client' => false, 'admin' => false];
        }

        return [
            'client' => $request->client_reminded_at ? false : $this->sendAndMark(
                $request,
                $request->email,
                'client_reminder',
                'client_reminded_at'
            ),
            'admin' => $request->admin_reminded_at ? false : $this->sendAndMark(
                $request,
                $this->adminAddress(),
                'admin_reminder',
                'admin_reminded_at'
            ),
        ];
    }

    protected function sendAndMark(SgiAccountRequest $request, ?string $to, string $type, string $field): bool
    {
        $to = trim((string) $to);
        if ($to === '') {
            Log::warning('Email SGI non envoyé : destinataire manquant.', [
                'request_id' => $request->id,
                'type' => $type,
            ]);

            return false;
        }

        try {
            Mail::to($to)->send(new SgiAccountRequestNotification($request->fresh() ?? $request, $type));

            $request->forceFill([$field => now()])->save();

            return true;
        } catch (\Throwable $exception) {
            Log::error('Email SGI non envoyé.', [
                'request_id' => $request->id,
                'type' => $type,
                'to' => $to,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    protected function adminAddress(): ?string
    {
        return config('mail.admin_address') ?: config('mail.from.address');
    }
}
