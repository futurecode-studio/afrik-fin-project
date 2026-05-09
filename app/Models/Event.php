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
        'city', 'country', 'capacity', 'registration_count', 'featured_image',
        'seo_title', 'seo_description', 'is_featured', 'status', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'registration_opens_at' => 'datetime',
        'registration_closes_at' => 'datetime',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'is_featured' => 'boolean',
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

    public function isRegistrationOpen(): bool
    {
        if (!in_array($this->status, ['published','ongoing'])) return false;
        $now = now();
        if ($this->registration_opens_at && $now->lt($this->registration_opens_at)) return false;
        if ($this->registration_closes_at && $now->gt($this->registration_closes_at)) return false;
        if ($this->capacity > 0 && $this->registration_count >= $this->capacity) return false;
        return true;
    }

    public function seatsRemaining(): int
    {
        if ($this->capacity <= 0) return PHP_INT_MAX;
        return max(0, $this->capacity - $this->registration_count);
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
