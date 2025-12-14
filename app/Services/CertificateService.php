<?php

namespace App\Services;

use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    /**
     * Générer le certificat PDF pour une inscription
     */
    public function generateCertificate(Enrollment $enrollment)
    {
        // Vérifier que la formation est terminée
        if (!$enrollment->isCompleted()) {
            throw new \Exception('La formation n\'est pas encore terminée.');
        }

        // Générer le numéro de certificat si pas encore fait
        if (!$enrollment->hasCertificate()) {
            $enrollment->update([
                'certificate_number' => $this->generateCertificateNumber($enrollment),
                'certificate_issued_at' => now(),
            ]);
            $enrollment->refresh();
        }

        // Générer le PDF
        $pdf = Pdf::loadView('certificates.formation', [
            'enrollment' => $enrollment,
            'user' => $enrollment->user,
            'formation' => $enrollment->formation,
            'certificateNumber' => $enrollment->certificate_number,
            'issuedAt' => $enrollment->certificate_issued_at,
            'completedAt' => $enrollment->completed_at,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf;
    }

    /**
     * Télécharger le certificat PDF
     */
    public function downloadCertificate(Enrollment $enrollment)
    {
        $pdf = $this->generateCertificate($enrollment);
        
        $filename = 'certificat-' . $enrollment->certificate_number . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Afficher le certificat dans le navigateur
     */
    public function streamCertificate(Enrollment $enrollment)
    {
        $pdf = $this->generateCertificate($enrollment);
        
        return $pdf->stream('certificat-' . $enrollment->certificate_number . '.pdf');
    }

    /**
     * Sauvegarder le certificat sur le disque
     */
    public function saveCertificate(Enrollment $enrollment)
    {
        $pdf = $this->generateCertificate($enrollment);
        
        $path = 'certificates/' . $enrollment->certificate_number . '.pdf';
        
        Storage::put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Générer un numéro de certificat unique
     */
    protected function generateCertificateNumber(Enrollment $enrollment)
    {
        $prefix = 'CERT';
        $year = date('Y');
        $formationCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $enrollment->formation->titre), 0, 3));
        $uniqueId = str_pad($enrollment->id, 6, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$formationCode}-{$uniqueId}";
    }

    /**
     * Vérifier la validité d'un certificat
     */
    public function verifyCertificate(string $certificateNumber)
    {
        $enrollment = Enrollment::where('certificate_number', $certificateNumber)->first();
        
        if (!$enrollment) {
            return [
                'valid' => false,
                'message' => 'Certificat non trouvé.',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Certificat valide.',
            'data' => [
                'holder' => $enrollment->user->name,
                'formation' => $enrollment->formation->titre,
                'issued_at' => $enrollment->certificate_issued_at->format('d/m/Y'),
                'completed_at' => $enrollment->completed_at->format('d/m/Y'),
            ],
        ];
    }
}
