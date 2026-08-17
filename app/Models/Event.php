<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'category', 'event_type',
        'starts_at', 'ends_at', 'registration_opens_at', 'registration_closes_at',
        'location_name', 'location_lat', 'location_lng', 'location_address',
        'city', 'country',
        'online_platform', 'online_meeting_url', 'online_meeting_id',
        'online_meeting_passcode', 'online_access_instructions',
        'capacity', 'registration_count',         'featured_image',
        'seo_title', 'seo_description', 'is_featured', 'is_jeudi_opportunite', 'is_paid', 'status', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'registration_opens_at' => 'datetime',
        'registration_closes_at' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'is_featured' => 'boolean',
        'is_jeudi_opportunite' => 'boolean',
        'is_paid' => 'boolean',
        'capacity' => 'integer',
        'registration_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
        static::saved(fn () => static::forgetJeudiCaches());
        static::deleted(fn () => static::forgetJeudiCaches());
    }

    public static function forgetJeudiCaches(): void
    {
        cache()->forget('popup.jeudi.v1');
        cache()->forget('home.page.data.v9');
        cache()->forget('home.page.data.v10');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ticketTypes()
    {
        return $this->hasMany(EventTicketType::class)->orderBy('display_order');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function programItems()
    {
        return $this->hasMany(EventProgramItem::class)->orderBy('display_order');
    }

    public function speakers()
    {
        return $this->hasMany(EventSpeaker::class)->orderBy('display_order');
    }

    public function sponsors()
    {
        return $this->belongsToMany(Partner::class, 'event_partner')
            ->withPivot(['sponsorship_level', 'benefits_description', 'amount', 'is_featured', 'display_order'])
            ->orderByPivot('display_order');
    }

    public function documents()
    {
        return $this->hasMany(EventDocument::class)->orderBy('display_order');
    }

    public function galleries()
    {
        return $this->hasMany(EventGallery::class)->orderBy('display_order');
    }

    public function products()
    {
        return $this->hasMany(EventProduct::class);
    }

    public function orders()
    {
        return $this->hasMany(EventOrder::class);
    }

    public function waitlists()
    {
        return $this->hasMany(EventWaitlist::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('starts_at', '>=', now()->subDays(1));
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->whereIn('status', ['published','ongoing']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now())->whereIn('status', ['published','ongoing']);
    }

    public function scopeJeudiOpportunite($query)
    {
        return $query->where('is_jeudi_opportunite', true);
    }

    public static function nextJeudiPopup(): ?self
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasColumn('events', 'is_jeudi_opportunite')) {
                return null;
            }

            return cache()->remember('popup.jeudi.v1', 60, function () {
                return static::query()
                    ->jeudiOpportunite()
                    ->whereIn('status', ['published', 'ongoing'])
                    ->where(function ($q) {
                        $q->where('starts_at', '>=', now()->subHours(2))
                            ->orWhere(function ($q2) {
                                $q2->whereNotNull('ends_at')->where('ends_at', '>=', now());
                            });
                    })
                    ->orderBy('starts_at')
                    ->first();
            });
        } catch (\Throwable) {
            return null;
        }
    }

    public function isRegistrationOpen(): bool
    {
        if (! in_array($this->status, ['published', 'ongoing'], true)) {
            return false;
        }

        $now = now();

        // Événement déjà terminé (ou démarré sans date de fin) → inscriptions fermées
        if ($this->ends_at && $now->gte($this->ends_at)) {
            return false;
        }
        if (! $this->ends_at && $this->starts_at && $now->gte($this->starts_at)) {
            return false;
        }

        if ($this->registration_opens_at && $now->lt($this->registration_opens_at)) {
            return false;
        }

        // Fenêtre d'inscription fermée, ou à défaut fermeture au démarrage de l'événement
        if ($this->registration_closes_at) {
            if ($now->gt($this->registration_closes_at)) {
                return false;
            }
        } elseif ($this->starts_at && $now->gte($this->starts_at)) {
            return false;
        }

        if ($this->capacity > 0 && $this->registration_count >= $this->capacity) {
            return false;
        }

        return true;
    }

    public function isPast(): bool
    {
        if ($this->ends_at) {
            return $this->ends_at->isPast();
        }

        return (bool) ($this->starts_at && $this->starts_at->isPast());
    }

    public function registrationStatusLabel(): string
    {
        if ($this->isPast() || in_array($this->status, ['completed', 'cancelled', 'archived'], true)) {
            return 'Terminé';
        }

        if ($this->isRegistrationOpen()) {
            return 'Ouvert';
        }

        return 'Fermé';
    }

    /**
     * L’événement propose des types de billets (gratuits et/ou payants).
     */
    public function isOnlineOrHybrid(): bool
    {
        return in_array($this->event_type, ['online', 'hybrid'], true);
    }

    public function hasOnlineAccess(): bool
    {
        return $this->isOnlineOrHybrid() && filled($this->online_meeting_url);
    }

    public function onlinePlatformLabel(): string
    {
        return match ($this->online_platform) {
            'zoom' => 'Zoom',
            'teams' => 'Microsoft Teams',
            'meet' => 'Google Meet',
            'other' => 'Visioconférence',
            default => $this->online_platform ? Str::title($this->online_platform) : 'En ligne',
        };
    }

    public function usesTickets(): bool
    {
        return (bool) $this->is_paid;
    }

    /**
     * Inscription avec choix de billet (billets actifs configurés).
     */
    public function requiresTicketSelection(): bool
    {
        return $this->usesTickets() && $this->ticketTypes()->where('is_active', true)->exists();
    }

    public function activeTicketTypes()
    {
        return $this->ticketTypes()->where('is_active', true);
    }

    public function hasFreeTickets(): bool
    {
        return $this->ticketTypes()->where('is_active', true)->where('price', '<=', 0)->exists();
    }

    public function hasPaidTickets(): bool
    {
        return $this->ticketTypes()->where('is_active', true)->where('price', '>', 0)->exists();
    }

    /**
     * Mode tarifaire : none | free | paid | hybrid
     */
    public function pricingMode(): string
    {
        if (!$this->usesTickets()) {
            return 'free';
        }

        $hasFree = $this->hasFreeTickets();
        $hasPaid = $this->hasPaidTickets();

        if ($hasFree && $hasPaid) {
            return 'hybrid';
        }
        if ($hasPaid) {
            return 'paid';
        }
        if ($hasFree) {
            return 'free';
        }

        return 'none';
    }

    public function pricingLabel(): string
    {
        return match ($this->pricingMode()) {
            'hybrid' => 'Hybride',
            'paid' => 'Payant',
            'free' => 'Gratuit',
            'none' => 'Billets à configurer',
            default => 'Gratuit',
        };
    }

    public function pricingBadgeClasses(): string
    {
        return match ($this->pricingMode()) {
            'hybrid' => 'bg-[#eef3ff] text-[#001a61] border border-[#001a61]/30',
            'paid' => 'bg-[#fff8e1] text-[#7a5c00]',
            'none' => 'bg-amber-50 text-amber-800',
            default => 'bg-emerald-50 text-emerald-800',
        };
    }

    public function seatsRemaining(): int
    {
        if ($this->capacity <= 0) return PHP_INT_MAX;
        return max(0, $this->capacity - $this->registration_count);
    }

    public function publicUrl(): string
    {
        return route('event-detail', $this->slug);
    }

    public function ticketUrl(string $qrCode): string
    {
        return route('event.ticket.public', $qrCode);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Brouillon',
            'published' => 'Publié',
            'ongoing' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            'archived' => 'Archivé',
            default => $this->status,
        };
    }

    public function statusColorClasses(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-emerald-100 text-emerald-800',
            'ongoing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-primary/10 text-primary',
            'cancelled' => 'bg-red-100 text-red-800',
            'archived' => 'bg-muted text-muted-foreground',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
