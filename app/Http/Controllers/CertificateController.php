<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Télécharger le certificat d'une formation
     */
    public function download(Enrollment $enrollment)
    {
        // Vérifier que l'utilisateur est propriétaire de l'inscription
        if ($enrollment->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à télécharger ce certificat.');
        }

        // Vérifier que la formation est terminée
        if (!$enrollment->isCompleted()) {
            return back()->with('error', 'Vous devez terminer la formation pour obtenir votre certificat.');
        }

        return $this->certificateService->downloadCertificate($enrollment);
    }

    /**
     * Afficher le certificat dans le navigateur
     */
    public function view(Enrollment $enrollment)
    {
        // Vérifier que l'utilisateur est propriétaire de l'inscription
        if ($enrollment->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce certificat.');
        }

        // Vérifier que la formation est terminée
        if (!$enrollment->isCompleted()) {
            return back()->with('error', 'Vous devez terminer la formation pour obtenir votre certificat.');
        }

        return $this->certificateService->streamCertificate($enrollment);
    }

    /**
     * Vérifier la validité d'un certificat (public)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|string',
        ]);

        $result = $this->certificateService->verifyCertificate($request->certificate_number);

        return response()->json($result);
    }
}
