<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\ModuleQuiz;
use App\Models\UserActivityLog;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\DB;

class QuizAttemptService
{
    /**
     * @param  array<int, array<int>|int|null>  $answers  questionId => answerId|answerIds
     */
    public function complete(ModuleQuiz $quiz, int $userId, array $answers, ?\DateTimeInterface $startedAt = null): UserQuizAttempt
    {
        $quiz->load(['questions.answers']);

        $pointsTotal = (int) $quiz->questions->sum('points');
        $pointsObtenus = 0;
        $normalized = [];

        foreach ($quiz->questions as $question) {
            $raw = $answers[$question->id] ?? $answers[(string) $question->id] ?? null;
            $ids = is_array($raw) ? array_map('intval', $raw) : (isset($raw) ? [(int) $raw] : []);
            $normalized[$question->id] = $ids;

            if ($question->checkAnswers($ids)) {
                $pointsObtenus += (int) $question->points;
            }
        }

        $score = $pointsTotal > 0 ? (int) round(($pointsObtenus / $pointsTotal) * 100) : 0;
        $passed = $score >= (int) $quiz->score_minimum;

        return DB::transaction(function () use ($quiz, $userId, $normalized, $score, $pointsObtenus, $pointsTotal, $passed, $startedAt) {
            $attempt = UserQuizAttempt::create([
                'user_id' => $userId,
                'module_quiz_id' => $quiz->id,
                'score' => $score,
                'points_obtenus' => $pointsObtenus,
                'points_total' => $pointsTotal,
                'reponses' => $normalized,
                'is_passed' => $passed,
                'started_at' => $startedAt ?? now()->subMinutes(1),
                'completed_at' => now(),
            ]);

            if ($passed && $quiz->is_final) {
                $quiz->loadMissing('module');
                $formationId = $quiz->module?->formation_id;
                if ($formationId) {
                    $enrollment = Enrollment::where('user_id', $userId)
                        ->where('formation_id', $formationId)
                        ->whereIn('status', ['active', 'completed'])
                        ->first();
                    if ($enrollment && ! $enrollment->hasCertificate()) {
                        $enrollment->complete();
                        if ($enrollment->status !== 'completed') {
                            $enrollment->update(['status' => 'completed']);
                        }
                    }
                }
            }

            $quiz->loadMissing('module');
            UserActivityLog::record(
                $userId,
                UserActivityLog::QUIZ_SUBMIT,
                ($quiz->titre ?? 'Quiz').' — '.$score.'%',
                $quiz->module?->formation_id,
                null,
                ['module_quiz_id' => $quiz->id, 'score' => $score, 'passed' => $passed]
            );

            return $attempt;
        });
    }
}
