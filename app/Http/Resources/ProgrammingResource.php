<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgrammingResource extends JsonResource
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
            'color' => $this->color,
            'subject' => new SubjectResource($this->subject),
            'progressions' => ProgressionResource::collection($this->whenLoaded('progressions')),
            '_links' => [
                'self' => action([ClassController::class, 'show'], $this),
            ]
        ];
    }
}
