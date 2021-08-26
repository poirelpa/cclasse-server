<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\ProgrammingController;

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
            'class_id' => $this->class_id,
            //'class' => $this->whenLoaded('class_'),
            //TODO 'progressions' => ProgressionResource::collection($this->whenLoaded('progressions')),
            '_links' => [
                'self' => action([ProgrammingController::class, 'show'], $this),
            ]
        ];
    }
}
