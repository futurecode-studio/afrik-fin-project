<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel webinaire</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 620px; margin: 0 auto; padding: 20px;">
    <div style="background: #001a61; padding: 28px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 22px;">Rappel webinaire</h1>
        <p style="color: #ffbf00; margin: 10px 0 0 0; font-weight: bold;">
            {{ $daysBefore === 1 ? 'C’est demain' : 'Dans '.$daysBefore.' jours' }}
        </p>
    </div>

    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Bonjour <strong>{{ $registration->fullName() }}</strong>,</p>

        <p>
            Petit rappel : vous êtes inscrit à <strong>{{ $event->title }}</strong>.
            L’événement aura lieu le <strong>{{ $event->starts_at?->format('d/m/Y') }}</strong>
            à <strong>{{ $event->starts_at?->format('H:i') }}</strong>.
        </p>

        <div style="background: white; padding: 18px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffbf00;">
            <p style="margin: 0;"><strong>Date :</strong> {{ $event->starts_at?->format('d/m/Y') }}</p>
            <p style="margin: 6px 0 0;"><strong>Heure :</strong> {{ $event->starts_at?->format('H:i') }}@if($event->ends_at) - {{ $event->ends_at->format('H:i') }}@endif</p>
            <p style="margin: 6px 0 0;"><strong>Lieu :</strong> {{ $event->hasOnlineAccess() ? $event->onlinePlatformLabel() : ($event->location_name ?? $event->city ?? 'À préciser') }}</p>
        </div>

        @if($event->hasOnlineAccess())
            <p style="text-align: center; margin: 24px 0;">
                <a href="{{ $event->online_meeting_url }}" style="display: inline-block; background: #ffbf00; color: #261a00; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold;">
                    Rejoindre le webinaire
                </a>
            </p>

            <p style="font-size: 13px; color: #6b7280; word-break: break-all;">
                Lien de connexion : <a href="{{ $event->online_meeting_url }}" style="color: #001a61;">{{ $event->online_meeting_url }}</a>
            </p>

            @if($event->online_meeting_id || $event->online_meeting_passcode)
                <div style="background: #e7eeff; padding: 14px; border-radius: 8px; margin: 18px 0; font-size: 14px;">
                    @if($event->online_meeting_id)
                        <p style="margin: 0;"><strong>ID réunion :</strong> {{ $event->online_meeting_id }}</p>
                    @endif
                    @if($event->online_meeting_passcode)
                        <p style="margin: 6px 0 0;"><strong>Code secret :</strong> {{ $event->online_meeting_passcode }}</p>
                    @endif
                </div>
            @endif
        @else
            <p style="text-align: center; margin: 24px 0;">
                <a href="{{ $ticketUrl }}" style="display: inline-block; background: #001a61; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold;">
                    Voir mon ticket
                </a>
            </p>
        @endif

        <p style="font-size: 13px; color: #6b7280;">
            Page de l’événement : <a href="{{ $eventUrl }}" style="color: #001a61;">{{ $eventUrl }}</a>
        </p>

        <p style="margin-top: 28px;">À bientôt,<br><strong>L’équipe Africaine des Finances</strong></p>
    </div>
</body>
</html>
