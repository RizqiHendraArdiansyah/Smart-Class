<?php

namespace App\Livewire;

use App\Models\Kuis\KuisModel;
use App\Models\Kuis\KuisCobaModel;
use App\Models\Kuis\KuisJawabanModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class Kuis extends Component
{
    public $step = 'list';
    public $quizzes = [];
    public $selectedQuiz = null;
    public $questions = [];
    public $answers = [];
    public $userAnswers = [];
    public $timer = 0;
    public $score = null;
    public $passcodeInput = '';
    public $error = '';
    public $quizAttempt = null;
    public $currentQuestionIndex = 0;
    public $isSubmitted = false;
    public $filter = 'all'; // 'all', 'done', 'notyet'
    public $difficultyGroups = []; // Track questions by difficulty level
    public $currentDifficulty = 'easy'; // Track current difficulty level
    public $selectedDifficulty = null;
    public $passcodeVerified = false;

    protected $listeners = ['tickTimer' => 'tickTimer'];

    public function mount()
    {
        $user = Auth::user();
        $this->quizzes = KuisModel::with(['modul.kelas', 'pertanyaan'])
            ->whereIn('is_aktif', ['aktif', 'datang'])
            ->whereHas('modul', function ($q) use ($user) {
                $q->whereIn('class_id', $user->kelas->pluck('id'));
            })
            ->get()
            ->map(function ($quiz) use ($user) {
                $quiz->attempt = KuisCobaModel::where('quiz_id', $quiz->id)
                    ->where('user_id', $user->id)
                    ->orderByDesc('id')
                    ->first();
                return $quiz;
            });
    }

    public function selectQuiz($quizId, $difficulty = null)
    {
        $this->selectedQuiz = KuisModel::with('pertanyaan')->findOrFail($quizId);
        $user = Auth::user();
        if ($difficulty) {
            $sudah = KuisCobaModel::where('quiz_id', $quizId)
                ->where('user_id', $user->id)
                ->where('difficulty_level', $difficulty)
                ->exists();
            if ($sudah) {
                $attempt = KuisCobaModel::where('quiz_id', $quizId)
                    ->where('user_id', $user->id)
                    ->where('difficulty_level', $difficulty)
                    ->first();
                // Ambil jawaban user dan pertanyaan dari relasi jawaban
                $jawabanModels = KuisJawabanModel::where('quiz_attempt_id', $attempt->id)->with('pertanyaan')->get();
                $this->questions = $jawabanModels->map(function ($jawaban) {
                    $q = $jawaban->pertanyaan;
                    if ($q) {
                        $q->is_correct = $jawaban->is_correct;
                    }
                    return $q;
                })->filter()->values()->all(); // array of questions
                $this->userAnswers = $jawabanModels->pluck('answer', 'question_id')->toArray();



                $this->score = $attempt->score . ' / ' . count($this->questions);
                $this->step = 'result';
                return;
            }
            $this->selectedDifficulty = $difficulty;
            $this->startQuiz($difficulty);
            return;
        }
        $this->step = 'difficulty';
    }

    public function checkPasscode()
    {
        if ($this->passcodeInput === $this->selectedQuiz->passcode) {
            $this->passcodeVerified = true;
            $this->error = null;
            $this->startQuiz($this->selectedDifficulty);
        } else {
            $this->error = 'Passcode salah. Silakan coba lagi.';
        }
    }

    public function startQuiz($difficulty = null)
    {
        $allQuestions = $this->selectedQuiz->pertanyaan;
        $this->difficultyGroups = [
            'easy' => $allQuestions->where('difficulty_level', 'easy')->values(),
            'medium' => $allQuestions->where('difficulty_level', 'medium')->values(),
            'hard' => $allQuestions->where('difficulty_level', 'hard')->values()
        ];

        if ($difficulty) {
            $this->questions = $this->difficultyGroups[$difficulty];
            $this->currentDifficulty = $difficulty;
            if (count($this->questions) === 0) {
                $this->step = 'empty';
                return;
            }
        } else {
            // Find the first non-empty difficulty group
            $firstDifficulty = collect(['easy', 'medium', 'hard'])
                ->first(function ($difficulty) {
                    return count($this->difficultyGroups[$difficulty]) > 0;
                });
            if (!$firstDifficulty) {
                session()->flash('error', 'Tidak ada soal yang tersedia untuk kuis ini.');
                $this->step = 'list';
                return;
            }
            $this->questions = $this->difficultyGroups[$firstDifficulty];
            $this->currentDifficulty = $firstDifficulty;
        }
        $this->timer = ($this->selectedQuiz->time_limit_minutes ?? 10) * 60; // waktu dalam menit
        $this->step = 'quiz';
        $this->currentQuestionIndex = 0;
        $this->isSubmitted = false;
    }

    public function tickTimer()
    {
        if ($this->timer > 0) {
            $this->timer--;
        } else {
            $this->submitQuiz();
        }
    }

    public function submitQuiz()
    {
        $user = Auth::user();
        // Cegah submit ulang
        $sudah = KuisCobaModel::where('quiz_id', $this->selectedQuiz->id)
            ->where('user_id', $user->id)
            ->where('difficulty_level', $this->currentDifficulty)
            ->exists();

        if ($sudah) {
            // Jika sudah ada attempt, ambil jawaban dari database
            $attempt = KuisCobaModel::where('quiz_id', $this->selectedQuiz->id)
                ->where('user_id', $user->id)
                ->where('difficulty_level', $this->currentDifficulty)
                ->first();

            $jawabanModels = KuisJawabanModel::where('quiz_attempt_id', $attempt->id)->with('pertanyaan')->get();
            $this->questions = $jawabanModels->map(function ($jawaban) {
                $q = $jawaban->pertanyaan;
                if ($q) {
                    $q->is_correct = $jawaban->is_correct;
                }
                return $q;
            })->filter()->values()->all();

            $this->userAnswers = $jawabanModels->pluck('answer', 'question_id')->toArray();



            $this->score = $attempt->score . ' / ' . count($this->questions);
            $this->step = 'result';
            return;
        }

        // Simpan attempt baru
        $attempt = KuisCobaModel::create([
            'user_id' => $user->id,
            'quiz_id' => $this->selectedQuiz->id,
            'difficulty_level' => $this->currentDifficulty,
            'started_at' => Carbon::now()->subSeconds($this->timer),
            'completed_at' => Carbon::now(),
        ]);

        $score = 0;
        $maxScore = 0;
        $totalQuestions = 0;

        foreach ($this->questions as $q) {
            $userAnswer = $this->answers[$q->id] ?? '';
            $isCorrect = false;
            $pointsAwarded = 0;
            $totalQuestions++;



            if ($q->type === 'multiple_choice') {
                // Pastikan opsi di-decode
                $options = is_string($q->options) ? json_decode($q->options, true) : $q->options;

                // Cek berdasarkan nilai pilihan
                $correctIndex = strtoupper($q->correct_answer); // misal: 'B'
                $correctOption = $options[ord($correctIndex) - ord('A')]['option'] ?? null;

                $isCorrect = ($userAnswer === $correctOption);
                $pointsAwarded = $isCorrect ? ($q->points ?? 1) : 0;
            } elseif ($q->type === 'true_false') {
                $isCorrect = ($userAnswer === $q->correct_answer);
                $pointsAwarded = $isCorrect ? ($q->points ?? 1) : 0;
            }

            // Tambah skor dan catat jawaban
            $score += $pointsAwarded;
            $maxScore += ($q->points ?? 1);

            KuisJawabanModel::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $q->id,
                'answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'points_awarded' => $pointsAwarded,
            ]);

            // Simpan ke array jawaban user
            $this->userAnswers[$q->id] = $userAnswer;
            $q->is_correct = $isCorrect;


        }

        $finalScore = $totalQuestions > 0 ? $score : 0;
        $attempt->update(['score' => $finalScore]);

        $this->score = $finalScore . ' / ' . $maxScore;
        $this->step = 'result';
    }


    public function backToList()
    {
        $this->reset(['step', 'selectedQuiz', 'questions', 'answers', 'userAnswers', 'timer', 'score', 'passcodeInput', 'error']);
        $this->step = 'list';
        $this->mount();
    }
    public function pilihJawaban($questionId, $jawaban)
    {
        $this->answers[$questionId] = $jawaban;
    }

    public function resetAnswerForCurrentQuestion()
    {
        // Reset jawaban untuk pertanyaan yang sedang aktif
        if (isset($this->questions[$this->currentQuestionIndex])) {
            $currentQuestionId = $this->questions[$this->currentQuestionIndex]->id;
            unset($this->answers[$currentQuestionId]);
        }
    }



    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
            // Reset jawaban untuk pertanyaan baru
            $this->resetAnswerForCurrentQuestion();
        } else {
            // If we're at the end of current difficulty, move to next difficulty
            if ($this->currentDifficulty === 'easy' && count($this->difficultyGroups['medium']) > 0) {
                $this->questions = $this->difficultyGroups['medium'];
                $this->currentDifficulty = 'medium';
                $this->currentQuestionIndex = 0;
                // Reset jawaban untuk pertanyaan baru
                $this->resetAnswerForCurrentQuestion();
            } elseif ($this->currentDifficulty === 'medium' && count($this->difficultyGroups['hard']) > 0) {
                $this->questions = $this->difficultyGroups['hard'];
                $this->currentDifficulty = 'hard';
                $this->currentQuestionIndex = 0;
                // Reset jawaban untuk pertanyaan baru
                $this->resetAnswerForCurrentQuestion();
            }
        }
    }

    public function prevQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            // Reset jawaban untuk pertanyaan baru
            $this->resetAnswerForCurrentQuestion();
        } else {
            // If we're at the start of current difficulty, move to previous difficulty
            if ($this->currentDifficulty === 'medium' && count($this->difficultyGroups['easy']) > 0) {
                $this->questions = $this->difficultyGroups['easy'];
                $this->currentDifficulty = 'easy';
                $this->currentQuestionIndex = count($this->questions) - 1;
                // Reset jawaban untuk pertanyaan baru
                $this->resetAnswerForCurrentQuestion();
            } elseif ($this->currentDifficulty === 'hard' && count($this->difficultyGroups['medium']) > 0) {
                $this->questions = $this->difficultyGroups['medium'];
                $this->currentDifficulty = 'medium';
                $this->currentQuestionIndex = count($this->questions) - 1;
                // Reset jawaban untuk pertanyaan baru
                $this->resetAnswerForCurrentQuestion();
            }
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function getFilteredQuizzesProperty()
    {
        if (!$this->quizzes) {
            return collect();
        }

        if ($this->filter == 'done') {
            return $this->quizzes->where('sudah_dikerjakan', true)->where('is_aktif', 'aktif')->values();
        } elseif ($this->filter == 'notyet') {
            return $this->quizzes->where('sudah_dikerjakan', false)->where('is_aktif', 'aktif')->values();
        } elseif ($this->filter == 'upcoming') {
            return $this->quizzes->where('is_aktif', 'datang')->values();
        } elseif ($this->filter == 'all') {
            return $this->quizzes->values();
        }
        // Default: tampilkan semua yang aktif dan datang
        return $this->quizzes->values();
    }

    public function chooseDifficulty($difficulty)
    {
        $this->selectedDifficulty = $difficulty;

        if ($this->selectedQuiz->is_passcode_enabled) {
            $this->step = 'passcode';
        } else {
            $this->startQuiz($difficulty);
        }
    }

    public function backToDifficulty()
    {
        $this->step = 'difficulty';
        $this->passcodeInput = '';
        $this->error = null;
        $this->passcodeVerified = false;
    }

    public function render()
    {
        return view('livewire.kuis');
    }
}
