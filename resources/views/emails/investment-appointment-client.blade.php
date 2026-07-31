<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de rendez-vous</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Africaine des Finances</h1>
        <p style="color: #e0e7ff; margin: 10px 0 0 0;">Votre partenaire d'investissement</p>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1e3a8a; margin-top: 0;">Demande de rendez-vous reçue</h2>
        
        <p>Bonjour <strong>{{ $appointment->name }}</strong>,</p>
        
        <p>Nous avons bien reçu votre demande de rendez-vous pour un accompagnement en investissement.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3b82f6;">
            <h3 style="margin-top: 0; color: #1e3a8a;">Détails de votre demande</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Type d'investissement :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->getInvestmentTypeLabel() }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Email :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Téléphone :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->phone }}</td>
                </tr>
                @if($appointment->company)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Entreprise :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->company }}</td>
                </tr>
                @endif
                @if($appointment->investment_amount)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Montant estimé :</strong></td>
                    <td style="padding: 8px 0;">{{ number_format($appointment->investment_amount, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
                @if($appointment->preferred_date)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>Date préférée :</strong></td>
                    <td style="padding: 8px 0;">{{ $appointment->preferred_date->format('d/m/Y') }}</td>
                </tr>
                @endif
            </table>
            
            @if($appointment->message)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0; color: #6b7280;"><strong>Votre message :</strong></p>
                <p style="margin: 10px 0 0 0;">{{ $appointment->message }}</p>
            </div>
            @endif
        </div>
        
        <div style="background: #dbeafe; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0; color: #1e40af;">
                <strong>📞 Prochaines étapes :</strong><br>
                Un de nos conseillers vous contactera dans les plus brefs délais pour confirmer votre rendez-vous et discuter de vos objectifs d'investissement.
            </p>
        </div>
        
        <p>Si vous avez des questions, n'hésitez pas à nous contacter :</p>
        <ul style="list-style: none; padding: 0;">
            <li>📧 Email : contact@africainedesfinances.com</li>
            <li>📱 Téléphone : +229 01 44 21 82 09 / 01 66 55 51 21</li>
        </ul>
        
        <p style="margin-top: 30px;">Cordialement,<br><strong>L'équipe Africaine des Finances</strong></p>
    </div>
    
    <div style="text-align: center; padding: 20px; color: #6b7280; font-size: 12px;">
        <p>© 2025 Africaine des Finances - Tous droits réservés</p>
        <p>Opérateur agréé AMF-UMOA (N° AA/2022-03)</p>
    </div>
</body>
</html>
