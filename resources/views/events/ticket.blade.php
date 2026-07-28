<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket — {{ $registration->event->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #071F5A;
            background: #f5f7fa;
            padding: 24px;
        }
        .wrapper {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid #0A2E8C;
        }
        /* Header */
        .header {
            background: #0A2E8C;
            color: #fff;
            padding: 28px 24px;
            text-align: center;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .header .subtitle {
            font-size: 11px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /* Body */
        .body {
            padding: 28px 24px;
            text-align: center;
        }
        .ticket-num {
            font-size: 9px;
            color: #777;
            font-family: 'Courier New', monospace;
            margin-bottom: 16px;
            word-break: break-all;
        }
        .qr-wrap {
            margin: 8px auto 20px;
            width: 220px;
            height: 220px;
        }
        .qr-wrap img {
            width: 100%;
            height: 100%;
        }
        .participant-name {
            font-size: 22px;
            font-weight: 700;
            color: #071F5A;
            margin-bottom: 4px;
        }
        .participant-email {
            font-size: 12px;
            color: #555;
            margin-bottom: 8px;
        }
        .institution {
            font-size: 11px;
            color: #777;
            margin-bottom: 8px;
        }
        .badge {
            display: inline-block;
            background: #EEF2FC;
            color: #0A2E8C;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e0e4eb;
            margin: 16px 0;
        }
        /* Details */
        .details {
            width: 100%;
            margin: 0 auto;
        }
        .details td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 12px 4px;
        }
        .details td + td {
            border-left: 1px solid #e0e4eb;
        }
        .details .label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .details .value {
            font-size: 13px;
            font-weight: 700;
            color: #071F5A;
        }
        /* Footer */
        .footer {
            background: #f8f9fb;
            padding: 14px 24px;
            font-size: 9px;
            color: #999;
            text-align: center;
            line-height: 1.5;
        }
        .footer strong {
            color: #0A2E8C;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ $registration->event->title }}</h1>
            <div class="subtitle">Africaine des Finances — Ticket officiel</div>
        </div>
        <div class="body">
            <p class="ticket-num">N° {{ $registration->qr_code }}</p>

            <div class="qr-wrap">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(220)->generate($registration->qr_code)) }}" alt="QR Code">
            </div>

            <p class="participant-name">{{ $registration->fullName() }}</p>
            <p class="participant-email">{{ $registration->email }}</p>

            @if($registration->institution_name)
                <p class="institution">{{ $registration->institution_name }}</p>
            @endif

            @if($registration->ticketType)
                <span class="badge">{{ $registration->ticketType->name }}</span>
            @endif

            <hr class="divider">

            <table class="details">
                <tr>
                    <td>
                        <div class="label">Date</div>
                        <div class="value">{{ $registration->event->starts_at?->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="label">Heure</div>
                        <div class="value">{{ $registration->event->starts_at?->format('H:i') }}</div>
                    </td>
                    <td>
                        <div class="label">Lieu</div>
                        <div class="value">{{ $registration->event->location_name ?? $registration->event->city ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            @if($registration->event->hasOnlineAccess())
                <hr class="divider">
                <div style="text-align: left; background: #e7eeff; border-radius: 10px; padding: 14px; margin-top: 8px;">
                    <div class="label" style="margin-bottom: 8px; color: #001a61; font-weight: 700;">Accès {{ $registration->event->onlinePlatformLabel() }}</div>
                    <p style="font-size: 11px; word-break: break-all; margin-bottom: 8px;">
                        <a href="{{ $registration->event->online_meeting_url }}" style="color: #001a61;">{{ $registration->event->online_meeting_url }}</a>
                    </p>
                    @if($registration->event->online_meeting_id)
                        <p style="font-size: 11px; margin-bottom: 4px;"><strong>ID :</strong> {{ $registration->event->online_meeting_id }}</p>
                    @endif
                    @if($registration->event->online_meeting_passcode)
                        <p style="font-size: 11px; margin-bottom: 4px;"><strong>Code :</strong> {{ $registration->event->online_meeting_passcode }}</p>
                    @endif
                    @if($registration->event->online_access_instructions)
                        <p style="font-size: 10px; color: #555; margin-top: 8px;">{{ $registration->event->online_access_instructions }}</p>
                    @endif
                </div>
            @endif
        </div>
        <div class="footer">
            <strong>Agréé AMF-UMOA N° AA/2022-03</strong><br>
            @if($registration->event->hasOnlineAccess())
                Utilisez le lien de visioconférence ci-dessus le jour J.<br>
            @else
                Présentez ce ticket à l'entrée. Toute reproduction est interdite.<br>
            @endif
            Conservez ce QR code — il est unique et personnel.
        </div>
    </div>
</body>
</html>
