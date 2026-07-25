<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use App\Models\Contact;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Formation;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserQuizAttempt;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public string $period = '30';

    public function updatedPeriod(): void
    {
        // Livewire re-render + charts via @script / livewire:navigated
    }

    public function render()
    {
        $days = (int) $this->period;
        $from = now()->subDays($days - 1)->startOfDay();

        $data = cache()->remember("admin.dashboard.analytics.{$days}.v1", 60, function () use ($from, $days) {
            $revenueTotal = (float) Payment::query()->where('status', 'completed')->sum('amount');
            $revenuePeriod = (float) Payment::query()
                ->where('status', 'completed')
                ->where('created_at', '>=', $from)
                ->sum('amount');
            $revenuePrev = (float) Payment::query()
                ->where('status', 'completed')
                ->whereBetween('created_at', [$from->copy()->subDays($days), $from->copy()->subSecond()])
                ->sum('amount');

            $usersTotal = User::count();
            $usersPeriod = User::where('created_at', '>=', $from)->count();
            $usersPrev = User::whereBetween('created_at', [$from->copy()->subDays($days), $from->copy()->subSecond()])->count();

            $enrollmentsTotal = Enrollment::count();
            $enrollmentsPeriod = Enrollment::where('created_at', '>=', $from)->count();
            $avgProgress = (float) (Enrollment::whereIn('status', ['active', 'completed'])->avg('progress') ?? 0);
            $completedEnrollments = Enrollment::where(function ($q) {
                $q->where('status', 'completed')->orWhere('progress', '>=', 100);
            })->count();
            $completionRate = $enrollmentsTotal > 0
                ? round(($completedEnrollments / $enrollmentsTotal) * 100, 1)
                : 0;

            $paymentsOk = Payment::where('status', 'completed')->count();
            $paymentsPending = Payment::where('status', 'pending')->count();
            $paymentsFailed = Payment::where('status', 'failed')->count();

            // Séries journalières
            $labels = [];
            $period = CarbonPeriod::create($from, now()->endOfDay());
            foreach ($period as $date) {
                $labels[] = $date->format('d/m');
            }

            $usersByDay = $this->dailyCounts(User::class, $from);
            $enrollmentsByDay = $this->dailyCounts(Enrollment::class, $from);
            $revenueByDay = $this->dailySums(Payment::class, $from, 'amount', fn ($q) => $q->where('status', 'completed'));
            $paymentsByDay = $this->dailyCounts(Payment::class, $from, fn ($q) => $q->where('status', 'completed'));

            $fillSeries = function (array $map) use ($labels, $from) {
                $series = [];
                foreach ($labels as $i => $label) {
                    $key = $from->copy()->addDays($i)->format('Y-m-d');
                    $series[] = (float) ($map[$key] ?? 0);
                }

                return $series;
            };

            // Progression buckets
            $progressBuckets = [
                '0–25%' => Enrollment::where('progress', '<', 25)->count(),
                '25–50%' => Enrollment::whereBetween('progress', [25, 49])->count(),
                '50–75%' => Enrollment::whereBetween('progress', [50, 74])->count(),
                '75–99%' => Enrollment::whereBetween('progress', [75, 99])->count(),
                '100%' => Enrollment::where('progress', '>=', 100)->count(),
            ];

            // Top formations
            $topFormations = Formation::query()
                ->withCount(['enrollments'])
                ->orderByDesc('enrollments_count')
                ->limit(6)
                ->get()
                ->map(fn ($f) => [
                    'titre' => $f->titre,
                    'count' => $f->enrollments_count,
                ]);

            // Activité récente
            $recentPayments = Payment::with(['user', 'formation'])
                ->latest()
                ->limit(6)
                ->get();

            $recentUsers = User::latest()->limit(5)->get(['id', 'name', 'email', 'created_at']);

            $quizAvg = (float) (UserQuizAttempt::whereNotNull('completed_at')->avg('score') ?? 0);
            $quizPassRate = UserQuizAttempt::whereNotNull('completed_at')->count() > 0
                ? round(
                    (UserQuizAttempt::whereNotNull('completed_at')->where('is_passed', true)->count()
                        / UserQuizAttempt::whereNotNull('completed_at')->count()) * 100,
                    1
                )
                : 0;

            return [
                'kpis' => [
                    'revenue' => $revenueTotal,
                    'revenue_period' => $revenuePeriod,
                    'revenue_delta' => $this->deltaPct($revenuePeriod, $revenuePrev),
                    'users' => $usersTotal,
                    'users_period' => $usersPeriod,
                    'users_delta' => $this->deltaPct($usersPeriod, $usersPrev),
                    'enrollments' => $enrollmentsTotal,
                    'enrollments_period' => $enrollmentsPeriod,
                    'avg_progress' => round($avgProgress, 1),
                    'completion_rate' => $completionRate,
                    'formations' => Formation::count(),
                    'events' => Event::count(),
                    'articles' => Article::count(),
                    'contacts_open' => Contact::query()
                        ->where(function ($q) {
                            $q->where('status', 'new')->orWhereNull('status');
                        })->count(),
                    'payments_ok' => $paymentsOk,
                    'payments_pending' => $paymentsPending,
                    'payments_failed' => $paymentsFailed,
                    'quiz_avg' => round($quizAvg, 1),
                    'quiz_pass' => $quizPassRate,
                ],
                'charts' => [
                    'labels' => $labels,
                    'revenue' => $fillSeries($revenueByDay),
                    'users' => $fillSeries($usersByDay),
                    'enrollments' => $fillSeries($enrollmentsByDay),
                    'payments' => $fillSeries($paymentsByDay),
                    'progress_labels' => array_keys($progressBuckets),
                    'progress_values' => array_values($progressBuckets),
                    'payment_status' => [
                        'labels' => ['Réussis', 'En attente', 'Échoués'],
                        'values' => [$paymentsOk, $paymentsPending, $paymentsFailed],
                    ],
                    'top_formations' => [
                        'labels' => $topFormations->pluck('titre')->map(fn ($t) => \Illuminate\Support\Str::limit($t, 28))->values()->all(),
                        'values' => $topFormations->pluck('count')->values()->all(),
                    ],
                ],
                'recentPayments' => $recentPayments,
                'recentUsers' => $recentUsers,
            ];
        });

        return view('livewire.pages.dashboard', [
            'kpis' => $data['kpis'],
            'charts' => $data['charts'],
            'recentPayments' => $data['recentPayments'],
            'recentUsers' => $data['recentUsers'],
            'days' => $days,
        ])
            ->extends('layouts.admin', ['title' => 'Tableau de Bord Admin'])
            ->section('content');
    }

    private function deltaPct(float|int $current, float|int $previous): ?float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * @param  class-string  $model
     * @return array<string, int>
     */
    private function dailyCounts(string $model, Carbon $from, ?callable $scope = null): array
    {
        $q = $model::query()->where('created_at', '>=', $from);
        if ($scope) {
            $scope($q);
        }

        return $q->select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'))
            ->groupBy('d')
            ->pluck('c', 'd')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    /**
     * @param  class-string  $model
     * @return array<string, float>
     */
    private function dailySums(string $model, Carbon $from, string $column, ?callable $scope = null): array
    {
        $q = $model::query()->where('created_at', '>=', $from);
        if ($scope) {
            $scope($q);
        }

        return $q->select(DB::raw('DATE(created_at) as d'), DB::raw("SUM({$column}) as s"))
            ->groupBy('d')
            ->pluck('s', 'd')
            ->map(fn ($v) => (float) $v)
            ->all();
    }
}
