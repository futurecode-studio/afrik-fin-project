<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket — {{ $registration->event->title }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; color: #071F5A; }
        .ticket { width: 100%; max-width: 600px; margin: 40px auto; border: 2px solid #0A2E8C; border-radius: 16px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 100%); color: #fff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { padding: 24px; background: #fff; text-align: center; }
        .qr { margin: 16px auto; width: 200px; height: 200px; }
        .name { font-size: 24px; font-weight: bold; margin: 8px 0; }
        .meta { color: #555; font-size: 14px; margin-bottom: 4px; }
        .badge { display: inline-block; background: #EEF2FC; color: #0A2E8C; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-top: 8px; }
        .footer { background: #f8f9fb; padding: 16px 24px; font-size: 12px; color: #777; text-align: center; border-top: 1px solid #eee; }
        .details { display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px; border-top: 1px solid #eee; }
        .details div { text-align: center; flex: 1; }
        .details div + div { border-left: 1px solid #eee; }
        .details p { margin: 0; font-size: 12px; color: #777; }
        .details strong { font-size: 14px; color: #071F5A; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>{{ $registration->event->title }}</h1>
            <p style="margin:8px 0 0; font-size:13px; opacity:0.9;">Africaine des Finances — Ticket officiel</p>
        </div>
        <div class="body">
            <p class="meta">N° {{ $registration->qr_code }}</p>
            <div class="qr">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(200)->generate($registration->qr_code)) }}" alt="QR Code" style="width:200px;height:200px;">
            </div>
            <p class="name">{{ $registration->fullName() }}</p>
            <p class="meta">{{ $registration->email }}</p>
            @if($registration->institution_name)
                <p class="meta">{{ $registration->institution_name }}</p>
            @endif
            @if($registration->ticketType)
                <span class="badge">{{ $registration->ticketType->name }}</span>
            @endif

            <div class="details">
                <div>
                    <p>Date</p>
                    <strong>{{ $registration->event->starts_at?->format('d/m/Y') }}</strong>
                </div>
                <div>
                    <p>Heure</p>
                    <strong>{{ $registration->event->starts_at?->format('H:i') }}</strong>
                </div>
                <div>
                    <p>Lieu</p>
                    <strong>{{ $registration->event->location_name ?? $registration->event->city ?? '-' }}</strong>
                </div>
            </div>
        </div>
        <div class="footer">
            Agréé AMF-UMOA N° AA/2022-03 — Présentez ce ticket à l'entrée. Toute reproduction est interdite.
        </div>
    </div>
</body>
</html>
