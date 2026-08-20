<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #001a61 0%, #0a2e8c 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 22px;">Africaine des Finances</h1>
        <p style="color: #ffbf00; margin: 10px 0 0 0; font-weight: bold;">Inscription confirmée</p>
    </div>

    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Bonjour <strong>{{ $registration->fullName() }}</strong>,</p>

        <p>Votre inscription à <strong>{{ $event->title }}</strong> est confirmée.
            @if($event->hasOnlineAccess())
                Retrouvez ci-dessous le lien de connexion {{ $event->onlinePlatformLabel() }}.
            @else
                Présentez le QR code ci-dessous (ou le PDF joint) à l'entrée pour valider votre présence.
            @endif
        </p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffbf00; text-align: center;">
            <p style="margin: 0 0 12px; font-size: 12px; color: #6b7280;">N° {{ $registration->qr_code }}</p>
            <img src="data:image/svg+xml;base64,{{ $qrSvgBase64 }}" alt="QR Code ticket" width="180" height="180" style="display: block; margin: 0 auto 16px;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 12px; text-align: left;">
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Date</td>
                    <td style="padding: 6px 0; font-weight: bold;">{{ $event->starts_at?->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Heure</td>
                    <td style="padding: 6px 0; font-weight: bold;">{{ $event->starts_at?->format('H:i') }} – {{ $event->ends_at?->format('H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Lieu</td>
                    <td style="padding: 6px 0; font-weight: bold;">{{ $event->location_name ?? $event->city ?? 'À préciser' }}</td>
                </tr>
            </table>
        </div>

        @if($event->hasOnlineAccess())
        <div style="background: #e7eeff; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #c5c5d4;">
            <p style="margin: 0 0 12px; font-size: 14px; font-weight: bold; color: #001a61;">Accès {{ $event->onlinePlatformLabel() }}</p>
            <p style="text-align: center; margin: 16px 0;">
                <a href="{{ $event->online_meeting_url }}" style="display: inline-block; background: #ffbf00; color: #261a00; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold;">
                    Rejoindre la réunion
                </a>
            </p>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Lien</td>
                    <td style="padding: 6px 0; word-break: break-all;"><a href="{{ $event->online_meeting_url }}" style="color: #001a61;">{{ $event->online_meeting_url }}</a></td>
                </tr>
                @if($event->online_meeting_id)
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">ID réunion</td>
                    <td style="padding: 6px 0; font-weight: bold;">{{ $event->online_meeting_id }}</td>
                </tr>
                @endif
                @if($event->online_meeting_passcode)
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Code secret</td>
                    <td style="padding: 6px 0; font-weight: bold;">{{ $event->online_meeting_passcode }}</td>
                </tr>
                @endif
            </table>
            @if($event->online_access_instructions)
                <p style="margin: 14px 0 0; font-size: 13px; color: #444652;">{{ $event->online_access_instructions }}</p>
            @endif
        </div>
        @endif

        <p style="text-align: center; margin: 24px 0;">
            <a href="{{ $ticketUrl }}" style="display: inline-block; background: #001a61; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold;">
                Voir / télécharger mon ticket
            </a>
        </p>

        <div style="background: #fff8e1; padding: 14px; border-radius: 8px; margin: 20px 0; font-size: 14px; color: #7a5c00;">
            @if($event->hasOnlineAccess())
                <strong>Le jour J :</strong> utilisez le bouton « Rejoindre la réunion » ci-dessus. Conservez aussi ce mail et le ticket PDF.
            @else
                <strong>Le jour J :</strong> gardez ce mail ou le PDF joint. Le QR code est scanné à l'entrée pour enregistrer votre présence.
            @endif
        </div>

        <p style="font-size: 13px; color: #6b7280;">
            Lien de l'événement : <a href="{{ $eventUrl }}" style="color: #001a61;">{{ $eventUrl }}</a>
        </p>

        <p style="margin-top: 28px;">À bientôt,<br><strong>L'équipe Africaine des Finances</strong></p>
    </div>

    <div style="text-align: center; padding: 16px; color: #6b7280; font-size: 12px;">
        Agréé AMF-UMOA N° AA/2022-03
    </div>
</body>
</html>
