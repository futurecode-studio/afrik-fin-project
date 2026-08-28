<?php

namespace App\Support;

class TeamCatalog
{
    /**
     * Membres affichés sur la page À propos (source unique).
     *
     * @return array<int, array{name: string, role: string, bio: string, tags: list<string>, image: string}>
     */
    public static function members(): array
    {
        return [
            [
                'name' => 'Marc C. Emmanuel EBO',
                'role' => 'Directeur général',
                'bio' => 'Docteur en sciences de gestion, enseignant-chercheur et financier, Marc C. Emmanuel EBO dirige Africaine des Finances. Il porte la vision d\'une finance accessible, pédagogique et orientée vers l\'épargne intelligente sur le marché financier régional.',
                'tags' => ['Direction', 'Gestion', 'Marchés financiers'],
                'image' => 'assets/images/team/ceo.jpeg',
            ],
            [
                'name' => 'Mohamed Fawaz ANGO',
                'role' => 'Conseiller financier',
                'bio' => 'Spécialiste de la relation client et de l\'accompagnement des investisseurs, Mohamed Fawaz ANGO est titulaire d\'un Master en Économie Appliquée et Politique de Développement. Il propose des solutions financières adaptées avec professionnalisme, transparence et création de valeur durable.',
                'tags' => ['Relation client', 'Investissement', 'Analyse économique'],
                'image' => 'assets/images/team/mohamed.PNG',
            ],
            [
                'name' => 'Cyrille Omondoun OGNONDOUN',
                'role' => 'Conseiller financier',
                'bio' => 'Diplômé en Économie et Finance Internationale de l\'Université de Parakou, Cyrille Omondoun OGNONDOUN accompagne particuliers, entreprises et institutions dans leurs projets d\'épargne et de valorisation de patrimoine sur le marché financier de l\'UEMOA.',
                'tags' => ['Épargne', 'Patrimoine', 'UEMOA'],
                'image' => 'assets/images/team/cyrille.jpeg',
            ],
            [
                'name' => 'Eureka HOUNKPATIN',
                'role' => 'Chargé de clientèle',
                'bio' => 'Eureka HOUNKPATIN accompagne les particuliers et les entreprises dans la découverte de solutions financières adaptées à leurs besoins, avec un engagement fondé sur l\'écoute, le professionnalisme et la construction de relations de confiance durables.',
                'tags' => ['Clientèle', 'Solutions financières', 'Confiance'],
                'image' => 'assets/images/team/eureka.jpg',
            ],
            [
                'name' => 'Micheline Gloria HOUNTONDJI',
                'role' => 'Conseillère clientèle',
                'bio' => 'Passionnée par la relation client et les marchés financiers, Micheline Gloria HOUNTONDJI accompagne chaque client dans la réalisation de ses projets d\'investissement avec écoute, rigueur et professionnalisme.',
                'tags' => ['Relation client', 'Investissement', 'Expérience client'],
                'image' => 'assets/images/team/micheline.jpeg',
            ],
            [
                'name' => 'Flora HESSOU',
                'role' => 'Conseillère clientèle',
                'bio' => 'Passionnée par la finance et l\'investissement, Flora HESSOU conseille, oriente et accompagne les particuliers et les institutionnels dans la gestion de leur trésorerie et la construction d\'un patrimoine solide.',
                'tags' => ['Finance', 'Investissement', 'Patrimoine'],
                'image' => 'assets/images/team/flora.jpeg',
            ],
            [
                'name' => 'Morel AGONSANOU',
                'role' => 'Conseiller financier',
                'bio' => 'Conseiller financier chez Africaine des Finances, Morel AGONSANOU offre un accompagnement personnalisé en épargne, investissement et gestion de patrimoine sur le Marché Financier Régional BRVM via les SGI et SGO.',
                'tags' => ['Épargne', 'BRVM', 'Gestion de patrimoine'],
                'image' => 'assets/images/team/morel.jpeg',
            ],
            [
                'name' => 'Donantin Rogatien Bij-Or AHOLOU',
                'role' => 'Conseiller financier',
                'bio' => 'Diplômé de l\'ENA du Bénin en Administration du Travail et de la Sécurité Sociale, Donantin Rogatien Bij-Or AHOLOU a développé une solide culture financière dans la microfinance avant de se spécialiser dans l\'accompagnement sur le marché financier régional de l\'UEMOA.',
                'tags' => ['Conseil financier', 'Microfinance', 'Patrimoine'],
                'image' => 'assets/images/team/donatin.JPG',
            ],
            [
                'name' => 'Angélique OKANA',
                'role' => 'Chargée de clientèle',
                'bio' => 'Conseil et accompagnement en investissement boursier. J\'accompagne les investisseurs dans leurs décisions et opérations sur le marché de la BRVM, avec une approche personnalisée, professionnelle et orientée vers leurs objectifs.',
                'tags' => ['Clientèle', 'Conseil', 'Investissement boursier'],
                'image' => 'assets/images/team/angelique.jpeg',
            ],
        ];
    }

    /** @var list<string> */
    private const HOME_PREVIEW_NAMES = [
        'Marc C. Emmanuel EBO',
        'Mohamed Fawaz ANGO',
        'Cyrille Omondoun OGNONDOUN',
        'Micheline Gloria HOUNTONDJI',
    ];

    /**
     * @return array<int, array{name: string, role: string, image: string}>
     */
    public static function homePreview(int $limit = 4): array
    {
        $byName = collect(self::members())->keyBy('name');

        return collect(self::HOME_PREVIEW_NAMES)
            ->take($limit)
            ->map(function (string $name) use ($byName) {
                $member = $byName->get($name);
                if (! $member) {
                    return null;
                }

                return [
                    'name' => $member['name'],
                    'role' => $member['role'],
                    'image' => $member['image'],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{nom: string, poste: string, description: string, attributs: string, is_active: bool, is_leadership: bool, order: int}>
     */
    public static function databaseRecords(): array
    {
        return collect(self::members())
            ->values()
            ->map(fn (array $member, int $index) => [
                'nom' => $member['name'],
                'poste' => $member['role'],
                'description' => $member['bio'],
                'attributs' => implode(', ', $member['tags']),
                'contact' => null,
                'email' => null,
                'photo' => null,
                'is_active' => true,
                'is_leadership' => $index === 0,
                'order' => $index + 1,
            ])
            ->all();
    }
}
