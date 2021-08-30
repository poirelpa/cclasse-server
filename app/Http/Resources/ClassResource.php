<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ProgressionController;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $this->year,
            'user_id' => $this->user->id,
            'levels' => LevelResource::collection($this->levels),
            'progressions' => ProgressionResource::collection($this->whenLoaded('progressions')),
            'subjects' => ProgressionResource::collection($this->whenLoaded('subjects')),
            '_links' => [
                'self' => action([ClassController::class, 'show'], $this),
                'progressions' => action([ProgressionController::class, 'index'], $this),
            ]
        ];
    }
}
