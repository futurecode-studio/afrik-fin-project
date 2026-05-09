<?php

namespace App\Console\Commands;

use App\Models\EventRegistration;
use App\Services\EventCommunicationService;
use Illuminate\Console\Command;

class EventRemindersCommand extends Command
{
    protected $signature = 'event:reminders {--dry-run : Afficher seulement, sans envoyer}';
    protected $description = 'Envoie les rappels email J-7 et J-1 avant les événements';

    public function handle(EventCommunicationService $service): int
    {
        $now = now();

        // J-7
        $sevenDays = $now->copy()->addDays(7)->startOfDay();
        $registrationsJ7 = EventRegistration::whereHas('event', function ($q) use ($sevenDays) {
                $q->whereDate('starts_at', $sevenDays->toDateString())
                  ->whereIn('status', ['published','ongoing']);
            })
            ->whereIn('status', ['registered','confirmed'])
            ->get();

        $this->info("Rappels J-7 : {$registrationsJ7->count()} inscriptions trouvées.");
        foreach ($registrationsJ7 as $reg) {
            if (!$this->option('dry-run')) {
                $service->sendReminder($reg, 7);
            }
            $this->line("  → {$reg->fullName()} ({$reg->email}) pour {$reg->event->title}");
        }

        // J-1
        $oneDay = $now->copy()->addDay()->startOfDay();
        $registrationsJ1 = EventRegistration::whereHas('event', function ($q) use ($oneDay) {
                $q->whereDate('starts_at', $oneDay->toDateString())
                  ->whereIn('status', ['published','ongoing']);
            })
            ->whereIn('status', ['registered','confirmed'])
            ->get();

        $this->info("Rappels J-1 : {$registrationsJ1->count()} inscriptions trouvées.");
        foreach ($registrationsJ1 as $reg) {
            if (!$this->option('dry-run')) {
                $service->sendReminder($reg, 1);
            }
            $this->line("  → {$reg->fullName()} ({$reg->email}) pour {$reg->event->title}");
        }

        return self::SUCCESS;
    }
}
