<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAnswerRequest;
use App\Http\Requests\Api\UpdateAnswerRequest;
use App\Http\Resources\Api\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use App\Services\AnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnswerController extends Controller
{
    public function __construct(private readonly AnswerService $answerService)
    {
    }

    public function index(Question $question): AnonymousResourceCollection
    {
        $answers = $this->answerService->paginateByQuestion($question);

        return AnswerResource::collection($answers);
    }

    public function store(StoreAnswerRequest $request): JsonResponse
    {
        $answer = $this->answerService->create($request->validated());

        return (new AnswerResource($answer))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Answer $answer): AnswerResource
    {
        return new AnswerResource($answer);
    }

    public function update(UpdateAnswerRequest $request, Answer $answer): AnswerResource
    {
        $answer = $this->answerService->update($answer, $request->validated());

        return new AnswerResource($answer);
    }

    public function destroy(Answer $answer): JsonResponse
    {
        $this->answerService->delete($answer);

        return response()->json(['success' => true, 'message' => 'Answer deleted successfully.']);
    }
}
