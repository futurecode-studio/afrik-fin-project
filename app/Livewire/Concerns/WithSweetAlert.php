<?php

namespace App\Livewire\Concerns;

trait WithSweetAlert
{
    /**
     * Notification SweetAlert2 (toast ou modal selon le type).
     */
    protected function swal(string $type, string $message, ?string $title = null, bool $toast = true, bool $html = false): void
    {
        $type = $type === 'message' ? 'success' : $type;

        $titles = [
            'success' => 'Succès',
            'error' => 'Erreur',
            'warning' => 'Attention',
            'info' => 'Information',
        ];

        $isHtml = $html || str_contains($message, '<');

        $this->dispatch('swal',
            type: $type,
            title: $title ?? ($titles[$type] ?? ''),
            message: $isHtml ? '' : $message,
            html: $isHtml ? $message : null,
            toast: $toast && $type !== 'error' && ! $isHtml,
        );
    }

    protected function swalSuccess(string $message, ?string $title = null): void
    {
        $this->swal('success', $message, $title);
    }

    protected function swalError(string $message, ?string $title = null): void
    {
        $this->swal('error', $message, $title, toast: false);
    }

    protected function swalInfo(string $message, ?string $title = null): void
    {
        $this->swal('info', $message, $title);
    }

    protected function swalWarning(string $message, ?string $title = null): void
    {
        $this->swal('warning', $message, $title);
    }

    /**
     * Flash session + Swal (utile avant un redirect full-page).
     */
    protected function flashSwal(string $type, string $message): void
    {
        $type = $type === 'message' ? 'success' : $type;
        session()->flash($type, $message);
        $this->swal($type, $message);
    }
}
