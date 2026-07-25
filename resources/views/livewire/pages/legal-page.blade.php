<div class="max-w-3xl mx-auto px-4 py-16">
    <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Informations légales</p>
    <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">{{ $pageTitle }}</h1>
    <p class="text-sm text-[#757683] mt-2">Dernière mise à jour : {{ now()->format('d/m/Y') }}</p>

    <article class="mt-10 prose prose-slate max-w-none text-[#444652] space-y-6">
        @switch($slug)
            @case('mentions-legales')
                <p><strong>Éditeur :</strong> Africaine des Finances — société de services financiers et de formation agréée.</p>
                <p><strong>Siège :</strong> Cotonou, République du Bénin.</p>
                <p><strong>Contact :</strong> contact@africainedesfinances.com</p>
                <p><strong>Directeur de la publication :</strong> Direction générale.</p>
                <p><strong>Hébergement :</strong> infrastructure cloud sécurisée dédiée à la plateforme.</p>
                @break
            @case('cgu')
                <p>L’accès et l’utilisation de la plateforme Africaine des Finances impliquent l’acceptation des présentes CGU.</p>
                <p>Les contenus (marchés, formations, outils) sont fournis à titre informatif et pédagogique. Toute utilisation commerciale non autorisée est interdite.</p>
                <p>L’utilisateur s’engage à fournir des informations exactes et à préserver la confidentialité de ses identifiants.</p>
                <p>Africaine des Finances se réserve le droit de suspendre un compte en cas d’usage abusif.</p>
                @break
            @case('confidentialite')
                <p>Nous collectons les données nécessaires à la gestion de votre compte, de vos inscriptions et de votre parcours pédagogique.</p>
                <p>Les données ne sont pas vendues. Elles peuvent être traitées par des prestataires techniques sous contrat de confidentialité.</p>
                <p>Vous disposez d’un droit d’accès, de rectification et de suppression via votre espace client ou le support.</p>
                @break
            @case('cookies')
                <p>Des cookies techniques sont utilisés pour l’authentification, la sécurité et la mesure d’audience anonymisée.</p>
                <p>Vous pouvez configurer votre navigateur pour refuser les cookies non essentiels. Certaines fonctionnalités peuvent alors être limitées.</p>
                @break
            @case('rgpd')
                <p>Le traitement des données personnelles respecte les principes de minimisation, finalité et sécurité.</p>
                <p>Pour toute demande liée à vos données : dpo@africainedesfinances.com (ou contact support).</p>
                <p>Les durées de conservation sont adaptées aux obligations légales et pédagogiques (comptes, certificats, transactions).</p>
                @break
            @case('conditions-formations')
                <p>L’accès aux formations payantes est conditionné au règlement validé. Les contenus restent la propriété d’Africaine des Finances.</p>
                <p>Les certificats sont délivrés après validation des critères de réussite (progression, quiz, examen).</p>
                <p>Les remboursements suivent la politique commerciale en vigueur au moment de l’achat.</p>
                @break
            @case('disclaimer')
                <p>Les informations de marché et outils de simulation ne constituent pas un conseil en investissement personnalisé.</p>
                <p>Tout investissement comporte un risque de perte en capital. Les performances passées ne préjugent pas des performances futures.</p>
                <p>Consultez un professionnel agréé avant toute décision d’investissement.</p>
                @break
        @endswitch
    </article>

    <nav class="mt-12 pt-8 border-t border-[#e7eeff] flex flex-wrap gap-3 text-sm">
        @foreach ([
            'mentions-legales' => 'Mentions',
            'cgu' => 'CGU',
            'confidentialite' => 'Confidentialité',
            'cookies' => 'Cookies',
            'rgpd' => 'RGPD',
            'conditions-formations' => 'Formations',
            'disclaimer' => 'Avertissement',
        ] as $key => $label)
            <a href="{{ route('legal.show', $key) }}" @class(['font-bold underline', 'text-[#001a61]' => $slug === $key, 'text-[#757683]' => $slug !== $key])>{{ $label }}</a>
        @endforeach
    </nav>
</div>
