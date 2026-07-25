<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * Get the primary role name for display
     */
    public function getPrimaryRoleAttribute(): string
    {
        $roleNames = $this->getRoleNames();
        return $roleNames->first() ?? 'Client';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'date_of_birth',
        'gender',
        'avatar',
        'is_active',
        'last_login_at',
        'interests_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'interests_completed_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Relation avec les inscriptions aux formations
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    /**
     * Relation avec les formations (via enrollments)
     */
    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'enrollments')
            ->withPivot(['status', 'amount_paid', 'enrolled_at', 'completed_at', 'progress'])
            ->withTimestamps();
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function watchlistItems()
    {
        return $this->hasMany(StockWatchlist::class)->orderBy('position');
    }

    public function watchlistStocks()
    {
        return $this->belongsToMany(Stock::class, 'stock_watchlists')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function interests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function hasCompletedInterests(): bool
    {
        return $this->interests_completed_at !== null;
    }

    /**
     * Vérifier si l'utilisateur est inscrit à une formation
     */
    public function isEnrolledIn(Formation $formation)
    {
        return $this->enrollments()
            ->where('formation_id', $formation->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
    }

    /**
     * Vérifier si l'utilisateur a une inscription en attente
     */
    public function hasPendingEnrollment(Formation $formation)
    {
        return $this->enrollments()
            ->where('formation_id', $formation->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Obtenir l'inscription à une formation
     */
    public function getEnrollment(Formation $formation)
    {
        return $this->enrollments()
            ->where('formation_id', $formation->id)
            ->first();
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->hasRole('admin');
    }

    /**
     * Vérifier si l'utilisateur est client
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }
}
