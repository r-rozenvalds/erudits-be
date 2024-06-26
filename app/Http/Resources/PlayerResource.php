<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'points' => $this->points,
            'tiebreaker_points' => $this->tiebreaker_points,
            'is_disqualified' => $this->is_disqualified,
            'player_answers' => PlayerAnswerResource::collection($this->player_answers),
            'open_answers' => OpenAnswerResource::collection($this->open_answers),
        ];
    }
}
