<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande de rendez-vous</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">⚠️ Nouvelle Demande</h1>
        <p style="color: #fee2e2; margin: 10px 0 0 0;">Rendez-vous d'investissement</p>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #dc2626; margin-top: 0;">Nouvelle demande de rendez-vous</h2>
        
        <p>Une nouvelle demande de rendez-vous pour un accompagnement en investissement a été reçue.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ef4444;">
            <h3 style="margin-top: 0; color: #dc2626;">Informations du client</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; width: 40%;"><strong>Nom :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Email :</strong></td>
                    <td style="padding: 8px 0;"><a href="mailto:{{ $appointment->email }}" style="color: #3b82f6;">{{ $appointment->email }}</a></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Téléphone :</strong></td>
                    <td style="padding: 8px 0;"><a href="tel:{{ $appointment->phone }}" style="color: #3b82f6;">{{ $appointment->phone }}</a></td>
                </tr>
                @if($appointment->company)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Entreprise :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->company }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Type d'investissement :</strong></td>
                    <td style="padding: 8px 0;"><span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 14px; font-weight: 600;">{{ $appointment->getInvestmentTypeLabel() }}</span></td>
                </tr>
                @if($appointment->investment_amount)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Montant estimé :</strong></td>
                    <td style="padding: 8px 0; font-weight: bold; color: #059669;">{{ number_format($appointment->investment_amount, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
                @if($appointment->preferred_date)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Date préférée :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->preferred_date->format('d/m/Y à H:i') }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Date de demande :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->created_at->format('d/m/Y à H:i') }}</td>
                </tr>
            </table>
            
            @if($appointment->message)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0; color: #6b7280;"><strong>Message du client :</strong></p>
                <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; margin-top: 10px;">
                    <p style="margin: 0;">{{ $appointment->message }}</p>
                </div>
            </div>
            @endif
        </div>
        
        <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b;">
            <p style="margin: 0; color: #92400e;">
                <strong>⚡ Action requise :</strong><br>
                Veuillez contacter ce client dans les plus brefs délais pour confirmer le rendez-vous et discuter de ses besoins en investissement.
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.appointments') }}" style="display: inline-block; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;">
                Voir dans le tableau de bord
            </a>
        </div>
    </div>
    
    <div style="text-align: center; padding: 20px; color: #6b7280; font-size: 12px;">
        <p>© 2025 Africaine des Finances - Système de gestion des rendez-vous</p>
    </div>
</body>
</html>
