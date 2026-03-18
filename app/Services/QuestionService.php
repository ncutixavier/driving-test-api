<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionService
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Question::with(['category', 'answers']);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }

        if (!empty($filters['search'])) {
            $query->where('question', 'like', "%{$filters['search']}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): Question
    {
        $answers = $data['answers'] ?? null;
        unset($data['answers']);

        $question = Question::create($data);

        if (!empty($answers)) {
            $question->answers()->createMany($answers);
        }

        return $question->load(['category', 'answers']);
    }

    public function update(Question $question, array $data): Question
    {
        $question->update($data);

        return $question->load(['category', 'answers']);
    }

    public function delete(Question $question): void
    {
        $question->delete();
    }

    public function random(int $count = 10, ?int $categoryId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Question::with(['category', 'answers'])->active();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->inRandomOrder()->limit($count)->get();
    }
}
