<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Pagination\LengthAwarePaginator;

class AnswerService
{
    public function paginateByQuestion(Question $question, int $perPage = 15): LengthAwarePaginator
    {
        return $question->answers()->latest()->paginate($perPage);
    }

    public function create(array $data): Answer
    {
        return Answer::create($data);
    }

    public function update(Answer $answer, array $data): Answer
    {
        $answer->update($data);

        return $answer;
    }

    public function delete(Answer $answer): void
    {
        $answer->delete();
    }
}
