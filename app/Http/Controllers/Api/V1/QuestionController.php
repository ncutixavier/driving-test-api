<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreQuestionRequest;
use App\Http\Requests\Api\UpdateQuestionRequest;
use App\Http\Resources\Api\QuestionResource;
use App\Models\Question;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function __construct(private readonly QuestionService $questionService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $questions = $this->questionService->paginate(
            $request->only(['category_id', 'is_active', 'difficulty', 'search'])
        );

        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $question = $this->questionService->create($request->validated());

        return (new QuestionResource($question))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Question $question): QuestionResource
    {
        $question->load(['category', 'answers']);

        return new QuestionResource($question);
    }

    public function update(UpdateQuestionRequest $request, Question $question): QuestionResource
    {
        $question = $this->questionService->update($question, $request->validated());

        return new QuestionResource($question);
    }

    public function destroy(Question $question): JsonResponse
    {
        $this->questionService->delete($question);

        return response()->json(['success' => true, 'message' => 'Question deleted successfully.']);
    }

    public function random(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'count'       => ['nullable', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $questions = $this->questionService->random(
            $request->integer('count', 10),
            $request->integer('category_id') ?: null
        );

        return QuestionResource::collection($questions);
    }
}
