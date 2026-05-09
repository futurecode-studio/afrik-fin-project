<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #071F5A;
            background: #f5f7fa;
            padding: 24px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid #0A2E8C;
        }
        .header {
            background: #0A2E8C;
            color: #fff;
            padding: 28px 24px;
            text-align: center;
        }
        .header h1 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .header .subtitle { font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 1px; }
        .body { padding: 24px; }
        .section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #999; margin-bottom: 8px; margin-top: 16px; }
        .info-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; }
        .info-label { color: #777; }
        .info-value { font-weight: 600; color: #071F5A; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .items-table th { text-align: left; font-size: 10px; text-transform: uppercase; color: #999; padding: 8px 4px; border-bottom: 1px solid #e0e4eb; }
        .items-table td { font-size: 12px; padding: 10px 4px; border-bottom: 1px solid #f0f2f5; }
        .items-table .total-cell { font-weight: 700; text-align: right; }
        .total-row td { border-top: 2px solid #0A2E8C; font-weight: 700; font-size: 14px; }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .footer {
            background: #f8f9fb;
            padding: 14px 24px;
            font-size: 9px;
            color: #999;
            text-align: center;
            line-height: 1.5;
        }
        .qr-wrap {
            text-align: center;
            margin: 16px 0;
        }
        .qr-wrap img {
            width: 140px;
            height: 140px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Reçu de paiement</h1>
            <div class="subtitle">{{ $event->title }}</div>
        </div>
        <div class="body">
            <div style="text-align: center; margin-bottom: 16px;">
                <span class="badge {{ $order->status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                    {{ $order->status === 'paid' ? 'Payé' : $order->status }}
                </span>
            </div>

            <div class="section-title">Informations commande</div>
            <div class="info-row"><span class="info-label">N° commande</span><span class="info-value">{{ $order->order_number }}</span></div>
            <div class="info-row"><span class="info-label">Date</span><span class="info-value">{{ $order->created_at?->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="info-label">Méthode</span><span class="info-value">{{ strtoupper($order->payment_provider ?? 'N/A') }}</span></div>
            @if($order->payment_transaction_id)
            <div class="info-row"><span class="info-label">Transaction</span><span class="info-value">{{ $order->payment_transaction_id }}</span></div>
            @endif

            <div class="section-title">Articles</div>
            <table class="items-table">
                <thead>
                    <tr><th>Article</th><th>Qté</th><th class="total-cell">Total</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <span style="font-size: 10px; color: #777;">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA / unité</span>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td class="total-cell">{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td class="total-cell">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Retrait</div>
            <div style="font-size: 12px; color: #555; line-height: 1.6;">
                Présentez ce reçu le jour de l'événement pour retirer vos articles sur place.<br>
                <strong>Lieu :</strong> {{ $event->location_name ?? $event->city ?? 'Lieu à confirmer' }}<br>
                <strong>Date :</strong> {{ $event->starts_at?->format('d/m/Y') }}
            </div>

            <div class="qr-wrap">
                <img src="data:image/svg+xml;base64,{{ base64_encode(\QrCode::format('svg')->size(140)->generate($order->order_number)) }}" alt="QR Code">
                <p style="font-size: 10px; color: #999; margin-top: 4px;">{{ $order->order_number }}</p>
            </div>
        </div>
        <div class="footer">
            <strong>Agréé AMF-UMOA N° AA/2022-03</strong><br>
            Africaine des Finances — Reçu officiel. Conservez ce document pour le retrait sur place.<br>
            Toute reproduction est interdite.
        </div>
    </div>
</body>
</html>
