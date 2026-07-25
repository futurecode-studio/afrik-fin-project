<?php

namespace App\Services;

/**
 * Questionnaire profil investisseur (session).
 * Score → Conservateur / Équilibré / Dynamique.
 */
class InvestorProfileService
{
    public const SESSION_KEY = 'investor_profile';

    public function questions(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Parlez-nous de votre situation actuelle',
                'subtitle' => 'Quel est votre horizon d\'investissement principal ?',
                'options' => [
                    ['id' => 'a', 'label' => 'Moins de 2 ans', 'score' => 1],
                    ['id' => 'b', 'label' => '2 à 5 ans', 'score' => 2],
                    ['id' => 'c', 'label' => '5 à 10 ans', 'score' => 3],
                    ['id' => 'd', 'label' => 'Plus de 10 ans', 'score' => 4],
                ],
            ],
            [
                'id' => 2,
                'title' => 'Définissons vos objectifs financiers',
                'subtitle' => 'Quel est votre objectif prioritaire ?',
                'options' => [
                    ['id' => 'a', 'label' => 'Préserver mon capital', 'score' => 1],
                    ['id' => 'b', 'label' => 'Revenus réguliers', 'score' => 2],
                    ['id' => 'c', 'label' => 'Croissance équilibrée', 'score' => 3],
                    ['id' => 'd', 'label' => 'Maximiser la performance', 'score' => 4],
                ],
            ],
            [
                'id' => 3,
                'title' => 'Votre tolérance au risque',
                'subtitle' => 'Si votre portefeuille baisse de 15 % en un mois, vous…',
                'options' => [
                    ['id' => 'a', 'label' => 'Vendez immédiatement', 'score' => 1],
                    ['id' => 'b', 'label' => 'Réduisez progressivement', 'score' => 2],
                    ['id' => 'c', 'label' => 'Attendez et conservez', 'score' => 3],
                    ['id' => 'd', 'label' => 'Renforcez vos positions', 'score' => 4],
                ],
            ],
            [
                'id' => 4,
                'title' => 'Votre expérience',
                'subtitle' => 'Avez-vous déjà investi sur les marchés financiers ?',
                'options' => [
                    ['id' => 'a', 'label' => 'Jamais', 'score' => 1],
                    ['id' => 'b', 'label' => 'Quelques placements simples', 'score' => 2],
                    ['id' => 'c', 'label' => 'Actions / obligations régulièrement', 'score' => 3],
                    ['id' => 'd', 'label' => 'Portefeuille diversifié actif', 'score' => 4],
                ],
            ],
            [
                'id' => 5,
                'title' => 'Votre capacité d\'épargne',
                'subtitle' => 'Quelle part de votre épargne pouvez-vous investir sans la toucher ?',
                'options' => [
                    ['id' => 'a', 'label' => 'Moins de 10 %', 'score' => 1],
                    ['id' => 'b', 'label' => '10 à 30 %', 'score' => 2],
                    ['id' => 'c', 'label' => '30 à 50 %', 'score' => 3],
                    ['id' => 'd', 'label' => 'Plus de 50 %', 'score' => 4],
                ],
            ],
        ];
    }

    public function resolve(array $answers): array
    {
        $questions = collect($this->questions())->keyBy('id');
        $score = 0;
        $detail = [];

        foreach ($answers as $qid => $oid) {
            $q = $questions->get((int) $qid);
            if (! $q) {
                continue;
            }
            $opt = collect($q['options'])->firstWhere('id', $oid);
            if (! $opt) {
                continue;
            }
            $score += (int) $opt['score'];
            $detail[] = [
                'question' => $q['subtitle'],
                'answer' => $opt['label'],
                'score' => $opt['score'],
            ];
        }

        $max = count($this->questions()) * 4;
        $ratio = $max > 0 ? $score / $max : 0;

        if ($ratio < 0.4) {
            $type = 'conservateur';
            $label = 'Conservateur';
            $color = '#0a2e8c';
            $allocation = ['Obligations / monétaire' => 70, 'Mixte / FCP prudents' => 20, 'Actions BRVM' => 10];
            $desc = 'Vous privilégiez la préservation du capital et la stabilité des revenus.';
        } elseif ($ratio < 0.7) {
            $type = 'equilibre';
            $label = 'Équilibré';
            $color = '#ffbf00';
            $allocation = ['Obligations' => 40, 'FCP mixtes' => 35, 'Actions BRVM' => 25];
            $desc = 'Vous acceptez une volatilité modérée pour viser une croissance durable.';
        } else {
            $type = 'dynamique';
            $label = 'Dynamique';
            $color = '#16a34a';
            $allocation = ['Actions BRVM' => 55, 'FCP actions' => 30, 'Obligations' => 15];
            $desc = 'Vous visez la performance long terme et tolérez des variations marquées.';
        }

        return [
            'type' => $type,
            'label' => $label,
            'color' => $color,
            'score' => $score,
            'max_score' => $max,
            'description' => $desc,
            'allocation' => $allocation,
            'answers_detail' => $detail,
            'completed_at' => now()->toIso8601String(),
        ];
    }

    public function save(array $profile): void
    {
        session([self::SESSION_KEY => $profile]);
    }

    public function get(): ?array
    {
        return session(self::SESSION_KEY);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
