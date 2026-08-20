<?php

namespace App\Console\Commands;

use App\Models\SgiAccountRequest;
use App\Services\SgiAccountRequestCommunicationService;
use Illuminate\Console\Command;

class SgiAccountRequestRemindersCommand extends Command
{
    protected $signature = 'sgi:account-reminders {--dry-run : Affiche les demandes à relancer sans envoyer d’email}';

    protected $description = 'Relance automatiquement les demandes SGI / SGO restées en attente.';

    public function handle(SgiAccountRequestCommunicationService $communication): int
    {
        $requests = SgiAccountRequest::query()
            ->where('status', 'pending')
            ->whereNull('client_reminded_at')
            ->where('created_at', '<=', now()->subDays(2))
            ->oldest()
            ->get();

        if ($this->option('dry-run')) {
            $this->info($requests->count().' demande(s) SGI / SGO à relancer.');

            foreach ($requests as $request) {
                $this->line("#{$request->id} {$request->name} <{$request->email}> créée le {$request->created_at?->format('d/m/Y H:i')}");
            }

            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($requests as $request) {
            $result = $communication->sendReminder($request);

            if ($result['client'] || $result['admin']) {
                $sent++;
            }
        }

        $this->info($sent.' demande(s) relancée(s) sur '.$requests->count().' éligible(s).');

        return self::SUCCESS;
    }
}
