<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            padding: 20px;
            line-height: 1.6;
            color: #374151;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .email-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .email-header p {
            font-size: 16px;
            opacity: 0.95;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .email-body h2 {
            font-size: 22px;
            color: #111827;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .email-body p {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 16px;
            line-height: 1.7;
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        
        .reset-button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3), 0 2px 4px -1px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }
        
        .reset-button:hover {
            background-color: #1d4ed8;
            box-shadow: 0 6px 8px -1px rgba(37, 99, 235, 0.4), 0 3px 5px -1px rgba(37, 99, 235, 0.3);
        }
        
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 16px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        
        .info-box p {
            margin-bottom: 0;
            font-size: 14px;
            color: #1e40af;
        }
        
        .alternative-link {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }
        
        .alternative-link p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        
        .alternative-link a {
            color: #2563eb;
            word-break: break-all;
            text-decoration: none;
            font-size: 13px;
        }
        
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .email-footer p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .email-footer .company-name {
            font-weight: 600;
            color: #111827;
            font-size: 15px;
            margin-bottom: 12px;
        }
        
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 25px 0;
        }
        
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .email-header h1 {
                font-size: 24px;
            }
            
            .email-body {
                padding: 30px 20px;
            }
            
            .reset-button {
                padding: 12px 24px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>🔐 Réinitialisation de mot de passe</h1>
            <p>Africaine des Finances</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <h2>Bonjour !</h2>
            
            <p>
                Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.
            </p>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Réinitialiser mon mot de passe
                </a>
            </div>
            
            <div class="info-box">
                <p>
                    <strong>⏱️ Important :</strong> Ce lien de réinitialisation expirera dans {{ $expireMinutes }} minutes.
                </p>
            </div>
            
            <p>
                Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise. Votre compte reste sécurisé.
            </p>
            
            <!-- Alternative Link -->
            <div class="alternative-link">
                <p><strong>Le bouton ne fonctionne pas ?</strong></p>
                <p>Copiez et collez ce lien dans votre navigateur :</p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>
            
            <div class="divider"></div>
            
            <p style="font-size: 14px; color: #6b7280;">
                Cordialement,<br>
                <strong style="color: #111827;">L'équipe Africaine des Finances</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p class="company-name">Africaine des Finances</p>
            <p>Votre partenaire de confiance pour la gestion financière en Afrique</p>
            <p style="margin-top: 15px;">
                © {{ date('Y') }} Africaine des Finances. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
