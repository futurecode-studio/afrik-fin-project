<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'formation_id',
        'status',
        'amount_paid',
        'enrolled_at',
        'completed_at',
        'progress',
        'certificate_number',
        'certificate_issued_at',
        'completed_lessons',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'progress' => 'integer',
        'completed_lessons' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la formation
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Vérifier si l'inscription est active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Vérifier si l'inscription est en attente de paiement
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Scope pour les inscriptions actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour les inscriptions en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Activer l'inscription après paiement
     */
    public function activate()
    {
        $this->update([
            'status' => 'active',
            'enrolled_at' => now(),
        ]);
    }

    /**
     * Vérifier si une leçon spécifique a été complétée
     */
    public function hasCompletedLesson(int $lessonId): bool
    {
        return in_array($lessonId, $this->completed_lessons ?? []);
    }

    /**
     * Marquer une leçon comme complétée et recalculer la progression
     */
    public function markLessonCompleted(int $lessonId, int $totalLessons): void
    {
        $completed = $this->completed_lessons ?? [];

        if (!in_array($lessonId, $completed)) {
            $completed[] = $lessonId;
            $progress = $totalLessons > 0 ? round((count($completed) / $totalLessons) * 100) : 0;

            $data = ['completed_lessons' => $completed, 'progress' => $progress];

            if ($progress >= 100 && !$this->completed_at) {
                $data['completed_at'] = now();
                $data['certificate_number'] = $this->generateCertificateNumber();
                $data['certificate_issued_at'] = now();
                $data['status'] = 'completed';
            }

            $this->update($data);
        }
    }

    /**
     * Vérifier si la formation est terminée
     */
    public function isCompleted()
    {
        return $this->progress >= 100 && $this->completed_at !== null;
    }

    /**
     * Vérifier si le certificat a été délivré
     */
    public function hasCertificate()
    {
        return $this->certificate_number !== null && $this->certificate_issued_at !== null;
    }

    /**
     * Marquer la formation comme terminée et générer le certificat
     */
    public function complete()
    {
        $this->update([
            'progress' => 100,
            'completed_at' => now(),
            'certificate_number' => $this->generateCertificateNumber(),
            'certificate_issued_at' => now(),
        ]);
    }

    /**
     * Générer un numéro de certificat unique
     */
    protected function generateCertificateNumber()
    {
        $prefix = 'CERT';
        $year = date('Y');
        $formationCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $this->formation->titre), 0, 3));
        $uniqueId = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$formationCode}-{$uniqueId}";
    }

    /**
     * Scope pour les formations terminées
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at')->where('progress', 100);
    }
}
