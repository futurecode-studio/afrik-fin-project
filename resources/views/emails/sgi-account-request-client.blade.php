<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Confirmation de demande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; max-width: 640px; margin: 0 auto; padding: 24px;">
    <h1 style="color: #001a61; font-size: 22px;">Votre demande a bien été reçue</h1>

    <p>Bonjour {{ $request->name }},</p>

    <p>
        Nous avons bien reçu votre demande de mise en relation avec une SGI / SGO.
        L’équipe Africaine des Finances va revenir vers vous pour vous accompagner dans les prochaines étapes.
    </p>

    <div style="background: #f0f3ff; border-left: 4px solid #0a2e8c; padding: 14px 16px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Email :</strong> {{ $request->email }}</p>
        <p style="margin: 6px 0 0;"><strong>Téléphone :</strong> {{ $request->phone }}</p>
        @if ($request->admin_notes)
            <p style="margin: 6px 0 0;"><strong>Information :</strong> {{ $request->admin_notes }}</p>
        @endif
    </div>

    <p>
        Si vous avez déjà des documents prêts, gardez-les à portée de main :
        pièce d’identité, photo d’identité, justificatif de domicile et IFU selon votre situation.
    </p>

    <p style="margin-top: 28px;">À très bientôt,<br>L’équipe Africaine des Finances</p>
</body>
</html>
