<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FormationModule;
use App\Models\ModuleQuiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;

class ModuleQuizManager extends Component
{
    public FormationModule $module;
    public ?ModuleQuiz $quiz = null;
    
    public $showQuizModal = false;
    public $showQuestionModal = false;
    public $showDeleteQuestionModal = false;
    public $editQuizMode = false;
    public $editQuestionMode = false;

    // Champs Quiz
    public $quizTitre;
    public $quizDescription;
    public $quizDureeMinutes;
    public $quizScoreMinimum = 70;
    public $quizTentativesMax = 3;
    public $quizIsActive = true;
    public $quizAfficherCorrections = true;

    // Champs Question
    public $questionId;
    public $questionTexte;
    public $questionType = 'choix_unique';
    public $questionExplication;
    public $questionPoints = 1;
    public $questionOrdre;
    public $questionIsActive = true;
    
    // Réponses (tableau dynamique)
    public $reponses = [];

    public function mount(FormationModule $module)
    {
        $this->module = $module;
        $this->quiz = $module->quiz;
        $this->initReponses();
    }

    public function initReponses()
    {
        $this->reponses = [
            ['texte' => '', 'is_correct' => false],
            ['texte' => '', 'is_correct' => false],
        ];
    }

    // ========== QUIZ CRUD ==========

    public function openQuizModal()
    {
        if ($this->quiz) {
            $this->quizTitre = $this->quiz->titre;
            $this->quizDescription = $this->quiz->description;
            $this->quizDureeMinutes = $this->quiz->duree_minutes;
            $this->quizScoreMinimum = $this->quiz->score_minimum;
            $this->quizTentativesMax = $this->quiz->tentatives_max;
            $this->quizIsActive = $this->quiz->is_active;
            $this->quizAfficherCorrections = $this->quiz->afficher_corrections;
            $this->editQuizMode = true;
        } else {
            $this->resetQuizForm();
            $this->editQuizMode = false;
        }
        $this->showQuizModal = true;
    }

    public function closeQuizModal()
    {
        $this->showQuizModal = false;
        $this->resetQuizForm();
    }

    public function saveQuiz()
    {
        $this->validate([
            'quizTitre' => 'required|string|max:255',
            'quizDescription' => 'nullable|string',
            'quizDureeMinutes' => 'nullable|integer|min:1',
            'quizScoreMinimum' => 'required|integer|min:0|max:100',
            'quizTentativesMax' => 'required|integer|min:1',
        ]);

        $quizData = [
            'formation_module_id' => $this->module->id,
            'titre' => $this->quizTitre,
            'description' => $this->quizDescription,
            'duree_minutes' => $this->quizDureeMinutes,
            'score_minimum' => $this->quizScoreMinimum,
            'tentatives_max' => $this->quizTentativesMax,
            'is_active' => $this->quizIsActive,
            'afficher_corrections' => $this->quizAfficherCorrections,
        ];

        if ($this->editQuizMode && $this->quiz) {
            $this->quiz->update($quizData);
            session()->flash('message', 'Quiz modifié avec succès');
        } else {
            $this->quiz = ModuleQuiz::create($quizData);
            session()->flash('message', 'Quiz créé avec succès');
        }

        $this->closeQuizModal();
    }

    public function deleteQuiz()
    {
        if ($this->quiz) {
            $this->quiz->delete();
            $this->quiz = null;
            session()->flash('message', 'Quiz supprimé avec succès');
        }
    }

    private function resetQuizForm()
    {
        $this->quizTitre = 'Quiz - ' . $this->module->titre;
        $this->quizDescription = '';
        $this->quizDureeMinutes = null;
        $this->quizScoreMinimum = 70;
        $this->quizTentativesMax = 3;
        $this->quizIsActive = true;
        $this->quizAfficherCorrections = true;
    }

    // ========== QUESTION CRUD ==========

    public function openQuestionModal()
    {
        $this->resetQuestionForm();
        $this->editQuestionMode = false;
        $this->questionOrdre = $this->quiz->questions()->max('ordre') + 1;
        $this->showQuestionModal = true;
    }

    public function closeQuestionModal()
    {
        $this->showQuestionModal = false;
        $this->resetQuestionForm();
    }

    public function editQuestion($id)
    {
        $question = QuizQuestion::with('answers')->findOrFail($id);
        
        $this->questionId = $question->id;
        $this->questionTexte = $question->question;
        $this->questionType = $question->type;
        $this->questionExplication = $question->explication;
        $this->questionPoints = $question->points;
        $this->questionOrdre = $question->ordre;
        $this->questionIsActive = $question->is_active;
        
        // Charger les réponses
        $this->reponses = $question->answers->map(function ($answer) {
            return [
                'id' => $answer->id,
                'texte' => $answer->reponse,
                'is_correct' => $answer->is_correct,
            ];
        })->toArray();

        if (count($this->reponses) < 2) {
            $this->initReponses();
        }
        
        $this->editQuestionMode = true;
        $this->showQuestionModal = true;
    }

    public function saveQuestion()
    {
        $this->validate([
            'questionTexte' => 'required|string',
            'questionType' => 'required|in:choix_unique,choix_multiple,vrai_faux',
            'questionExplication' => 'nullable|string',
            'questionPoints' => 'required|integer|min:1',
            'reponses' => 'required|array|min:2',
            'reponses.*.texte' => 'required|string',
        ], [
            'questionTexte.required' => 'La question est obligatoire',
            'reponses.required' => 'Au moins 2 réponses sont requises',
            'reponses.*.texte.required' => 'Le texte de la réponse est obligatoire',
        ]);

        // Vérifier qu'au moins une réponse est correcte
        $hasCorrect = collect($this->reponses)->contains('is_correct', true);
        if (!$hasCorrect) {
            $this->addError('reponses', 'Au moins une réponse doit être marquée comme correcte');
            return;
        }

        $questionData = [
            'module_quiz_id' => $this->quiz->id,
            'question' => $this->questionTexte,
            'type' => $this->questionType,
            'explication' => $this->questionExplication,
            'points' => $this->questionPoints,
            'ordre' => $this->questionOrdre ?? 0,
            'is_active' => $this->questionIsActive,
        ];

        if ($this->editQuestionMode) {
            $question = QuizQuestion::findOrFail($this->questionId);
            $question->update($questionData);
            
            // Supprimer les anciennes réponses et recréer
            $question->answers()->delete();
        } else {
            $question = QuizQuestion::create($questionData);
        }

        // Créer les réponses
        foreach ($this->reponses as $index => $reponse) {
            if (!empty($reponse['texte'])) {
                QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'reponse' => $reponse['texte'],
                    'is_correct' => $reponse['is_correct'] ?? false,
                    'ordre' => $index,
                ]);
            }
        }

        session()->flash('message', $this->editQuestionMode ? 'Question modifiée avec succès' : 'Question créée avec succès');
        $this->closeQuestionModal();
    }

    public function confirmDeleteQuestion($id)
    {
        $this->questionId = $id;
        $this->showDeleteQuestionModal = true;
    }

    public function deleteQuestion()
    {
        $question = QuizQuestion::findOrFail($this->questionId);
        $question->delete();
        
        session()->flash('message', 'Question supprimée avec succès');
        $this->showDeleteQuestionModal = false;
        $this->questionId = null;
    }

    public function addReponse()
    {
        $this->reponses[] = ['texte' => '', 'is_correct' => false];
    }

    public function removeReponse($index)
    {
        if (count($this->reponses) > 2) {
            unset($this->reponses[$index]);
            $this->reponses = array_values($this->reponses);
        }
    }

    public function updatedQuestionType()
    {
        if ($this->questionType === 'vrai_faux') {
            $this->reponses = [
                ['texte' => 'Vrai', 'is_correct' => false],
                ['texte' => 'Faux', 'is_correct' => false],
            ];
        }
    }

    private function resetQuestionForm()
    {
        $this->questionId = null;
        $this->questionTexte = '';
        $this->questionType = 'choix_unique';
        $this->questionExplication = '';
        $this->questionPoints = 1;
        $this->questionOrdre = 0;
        $this->questionIsActive = true;
        $this->initReponses();
    }

    public function render()
    {
        $questions = $this->quiz ? $this->quiz->questions()->with('answers')->orderBy('ordre')->get() : collect();

        return view('livewire.admin.module-quiz-manager', [
            'questions' => $questions
        ])
            ->extends('layouts.admin', ['title' => 'Quiz - ' . $this->module->titre])
            ->section('content');
    }
}
