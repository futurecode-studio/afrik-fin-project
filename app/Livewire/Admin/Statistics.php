<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Article;
use App\Models\Formation;
use App\Models\Transaction;
use App\Models\NewsletterSubscriber;
use App\Models\Contact;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Statistics extends Component
{
    public $stats = [];
    public $recentTransactions = [];
    public $recentUsers = [];
    public $chartData = [];
    public $period = '30days'; // 7days, 30days, 90days, year

    public function mount()
    {
        $this->loadStatistics();
        $this->loadRecentData();
        $this->loadChartData();
    }

    public function loadStatistics()
    {
        // Statistiques générales
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalArticles = Article::count();
        $publishedArticles = Article::where('statut', 'publie')->count();
        $totalFormations = Formation::count();
        $totalTransactions = Transaction::count();
        $successfulTransactions = Transaction::successful()->count();
        $totalRevenue = Transaction::successful()->sum('amount');
        $totalFees = Transaction::successful()->sum('fees');
        $netRevenue = $totalRevenue - $totalFees;
        $totalSubscribers = NewsletterSubscriber::where('is_active', true)->count();
        $totalContacts = Contact::count();
        $pendingContacts = Contact::where('status', 'pending')->count();

        // Statistiques sur la période
        $startDate = $this->getStartDate();
        $newUsers = User::where('created_at', '>=', $startDate)->count();
        $newTransactions = Transaction::where('created_at', '>=', $startDate)->count();
        $periodRevenue = Transaction::successful()
            ->where('created_at', '>=', $startDate)
            ->sum('amount');

        // Taux de conversion (transactions réussies / total transactions)
        $conversionRate = $totalTransactions > 0 
            ? round(($successfulTransactions / $totalTransactions) * 100, 2) 
            : 0;

        // Revenu moyen par transaction réussie
        $avgTransactionAmount = $successfulTransactions > 0 
            ? round($totalRevenue / $successfulTransactions, 0) 
            : 0;

        // Statistiques aujourd'hui
        $todayUsers = User::whereDate('created_at', today())->count();
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $todayRevenue = Transaction::successful()
            ->whereDate('created_at', today())
            ->sum('amount');

        $this->stats = [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalArticles' => $totalArticles,
            'publishedArticles' => $publishedArticles,
            'totalFormations' => $totalFormations,
            'totalTransactions' => $totalTransactions,
            'successfulTransactions' => $successfulTransactions,
            'totalRevenue' => $totalRevenue,
            'totalFees' => $totalFees,
            'netRevenue' => $netRevenue,
            'totalSubscribers' => $totalSubscribers,
            'totalContacts' => $totalContacts,
            'pendingContacts' => $pendingContacts,
            'newUsers' => $newUsers,
            'newTransactions' => $newTransactions,
            'periodRevenue' => $periodRevenue,
            'conversionRate' => $conversionRate,
            'avgTransactionAmount' => $avgTransactionAmount,
            'todayUsers' => $todayUsers,
            'todayTransactions' => $todayTransactions,
            'todayRevenue' => $todayRevenue,
        ];
    }

    public function loadRecentData()
    {
        // 5 dernières transactions
        $this->recentTransactions = Transaction::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        // 5 derniers utilisateurs
        $this->recentUsers = User::latest()
            ->take(5)
            ->get();
    }

    public function loadChartData()
    {
        $startDate = $this->getStartDate();
        $days = $this->getPeriodDays();

        // Données pour le graphique des revenus
        $revenueData = [];
        $transactionData = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $dailyRevenue = Transaction::successful()
                ->whereDate('created_at', $date)
                ->sum('amount');
            
            $dailyTransactions = Transaction::whereDate('created_at', $date)->count();
            
            $revenueData[] = $dailyRevenue;
            $transactionData[] = $dailyTransactions;
        }

        $this->chartData = [
            'labels' => $labels,
            'revenue' => $revenueData,
            'transactions' => $transactionData,
        ];
    }

    public function changePeriod($period)
    {
        $this->period = $period;
        $this->loadStatistics();
        $this->loadChartData();
    }

    private function getStartDate()
    {
        return match($this->period) {
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            '90days' => Carbon::now()->subDays(90),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };
    }

    private function getPeriodDays()
    {
        return match($this->period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            'year' => 365,
            default => 30,
        };
    }

    public function render()
    {
        return view('livewire.admin.statistics')
            ->extends('layouts.admin', ['title' => 'Statistiques'])
            ->section('content');
    }
}
