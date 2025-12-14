<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Formation - {{ $formation->titre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #0d1b2a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .certificate {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #1e3a5f 0%, #d4af37 50%, #1e3a5f 100%);
        }

        .certificate::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #1e3a5f 0%, #d4af37 50%, #1e3a5f 100%);
        }

        .border-decoration {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #d4af37;
            border-radius: 5px;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            height: 60px;
            width: auto;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a5f;
            margin-top: 10px;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 20px 0;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }

        .recipient-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .recipient-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 20px;
            font-style: italic;
        }

        .completion-text {
            font-size: 14px;
            color: #333;
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto 20px;
        }

        .formation-title {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a5f;
            margin: 20px 0;
            padding: 15px 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 5px;
            display: inline-block;
        }

        .details {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a5f;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .signature {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            width: 150px;
            height: 1px;
            background: #333;
            margin: 0 auto 10px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }

        .certificate-number {
            position: absolute;
            bottom: 30px;
            right: 40px;
            font-size: 10px;
            color: #999;
        }

        .qr-code {
            position: absolute;
            bottom: 30px;
            left: 40px;
        }

        .seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 80px;
            height: 80px;
            border: 3px solid #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            color: #d4af37;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-decoration"></div>
        
        <div class="content">
            <div class="logo">
                <div class="logo-text">Africaine des Finances</div>
            </div>

            <h1 class="title">Certificat de Réussite</h1>
            <p class="subtitle">Certificate of Completion</p>

            <p class="recipient-label">Ce certificat est décerné à</p>
            <h2 class="recipient-name">{{ $user->name }}</h2>

            <p class="completion-text">
                Pour avoir complété avec succès la formation en ligne et démontré 
                les compétences requises dans le domaine de la finance et de l'investissement.
            </p>

            <div class="formation-title">{{ $formation->titre }}</div>

            <div class="details">
                <div class="detail-item">
                    <p class="detail-label">Date de début</p>
                    <p class="detail-value">{{ $enrollment->enrolled_at->format('d/m/Y') }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Date de fin</p>
                    <p class="detail-value">{{ $completedAt->format('d/m/Y') }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Durée</p>
                    <p class="detail-value">{{ $formation->duree }}</p>
                </div>
                <div class="detail-item">
                    <p class="detail-label">Niveau</p>
                    <p class="detail-value">{{ ucfirst($formation->niveau) }}</p>
                </div>
            </div>

            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <p class="signature-name">Direction Pédagogique</p>
                    <p class="signature-title">Africaine des Finances</p>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <p class="signature-name">Direction Générale</p>
                    <p class="signature-title">Africaine des Finances</p>
                </div>
            </div>
        </div>

        <div class="seal">
            Certifié<br>Authentique
        </div>

        <div class="certificate-number">
            N° {{ $certificateNumber }}<br>
            Délivré le {{ $issuedAt->format('d/m/Y') }}
        </div>
    </div>
</body>
</html>
