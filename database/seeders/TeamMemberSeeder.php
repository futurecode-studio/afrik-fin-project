<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'nom' => 'Dr. Kofi Annan',
                'poste' => 'Directeur Général',
                'attributs' => 'PhD Finance, Certifié AMF-UMOA, MBA',
                'description' => 'Fondateur et Directeur Général d\'Africaine des Finances. Plus de 15 ans d\'expérience dans les marchés financiers africains. Ancien analyste à la BRVM.',
                'contact' => '+229 01 44 21 82 09',
                'email' => 'k.annan@africainedesfinances.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'nom' => 'Marie Doumbouya',
                'poste' => 'Directrice Adjointe',
                'attributs' => 'Master Finance, Certification CFA',
                'description' => 'Responsable des opérations et du développement stratégique. Experte en analyse fondamentale et analyse technique.',
                'contact' => '+229 01 44 21 78 90',
                'email' => 'm.doumbouya@africainedesfinances.com',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'nom' => 'Jules Bakari',
                'poste' => 'Analyste Financier Senior',
                'attributs' => 'Master Marchés Financiers, Certification CNMS',
                'description' => 'Spécialiste des analyses sectorielles et de la sélection de valeurs mobiles. Plus de 10 ans d\'expérience.',
                'contact' => '+229 01 44 21 78 91',
                'email' => 'j.bakari@africainedesfinances.com',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'nom' => 'Fatou Diallo',
                'poste' => 'Responsable Formation',
                'attributs' => 'Master Pédagogie, Formateur Certifié',
                'description' => 'Coordonne les formations e-learning et présentielles. Expertise en education financière et développement de compétences.',
                'contact' => '+229 01 44 21 78 92',
                'email' => 'f.diallo@africainedesfinances.com',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'nom' => 'Georgia Gnancadja',
                'poste' => 'Chargée de Clientèle',
                'attributs' => 'Formation en Commerce International',
                'description' => 'Spécialisée dans la relation client et l\'accompagnement commercial. Sens de l\'écoute, capacité de persuasion et dynamisme pour proposer des solutions adaptées.',
                'contact' => '+229 01 44 21 78 95',
                'email' => 'g.gnancadja@africainedesfinances.com',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'nom' => 'Morel Agonsanou',
                'poste' => 'Chargé de Clientèle',
                'attributs' => 'Licence Commerce International',
                'description' => 'Domaine de la relation client et du développement commercial. Sens de l\'analyse, rigueur et professionnalisme pour des solutions performantes.',
                'contact' => '+229 01 44 21 78 96',
                'email' => 'm.agonsanou@africainedesfinances.com',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'nom' => 'Ognondoun Cyrille',
                'poste' => 'Chargé de Clientèle',
                'attributs' => 'Économie et Finance Internationale',
                'description' => 'Diplômé en Économie et Finance Internationale de l\'Université de Parakou. Accompagnement des investisseurs, personnes physiques, entreprises et institutions.',
                'contact' => '+229 01 44 21 78 97',
                'email' => 'o.cyrille@africainedesfinances.com',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'nom' => 'Micheline Gloria Mountondji',
                'poste' => 'Chargée de Clientèle',
                'attributs' => 'Marketing Communication et Commerce',
                'description' => 'Spécialisée dans la relation client et l\'accompagnement commercial. Sens de l\'écoute et capacité de persuasion pour des solutions adaptées aux profils investisseur.',
                'contact' => '+229 01 44 21 78 98',
                'email' => 'm.mountondji@africainedesfinances.com',
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::create($member);
        }
    }
}