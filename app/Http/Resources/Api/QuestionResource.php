<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'category_id' => $this->category_id,
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'question'    => $this->question,
            'image'       => $this->image,
            'difficulty'  => $this->difficulty,
            'explanation' => $this->explanation,
            'is_active'   => $this->is_active,
            'answers'     => AnswerResource::collection($this->whenLoaded('answers')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
