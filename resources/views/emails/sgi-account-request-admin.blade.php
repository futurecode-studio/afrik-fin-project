<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Nouvelle demande SGI / SGO</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; max-width: 680px; margin: 0 auto; padding: 24px;">
    <h1 style="color: #001a61; font-size: 22px;">Nouvelle demande de mise en relation</h1>

    <p>Une nouvelle demande SGI / SGO vient d’être déposée sur la plateforme.</p>

    <div style="background: #f8fafc; border: 1px solid #dbe3f0; padding: 16px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Client :</strong> {{ $request->name }}</p>
        <p style="margin: 6px 0 0;"><strong>Email :</strong> <a href="mailto:{{ $request->email }}">{{ $request->email }}</a></p>
        <p style="margin: 6px 0 0;"><strong>Téléphone :</strong> <a href="tel:{{ $request->phone }}">{{ $request->phone }}</a></p>
        <p style="margin: 6px 0 0;"><strong>Source :</strong> {{ $request->sourceLabel() }}</p>
        <p style="margin: 6px 0 0;"><strong>Date :</strong> {{ $request->created_at?->format('d/m/Y H:i') }}</p>
        @if ($request->message)
            <p style="margin: 12px 0 0;"><strong>Message :</strong><br>{{ $request->message }}</p>
        @endif
        @if ($request->admin_notes)
            <p style="margin: 12px 0 0;"><strong>Note :</strong><br>{{ $request->admin_notes }}</p>
        @endif
    </div>

    <p>
        Connectez-vous au tableau de bord admin pour traiter la demande et mettre son statut à jour.
    </p>
</body>
</html>
