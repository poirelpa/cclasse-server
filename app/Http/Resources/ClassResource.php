<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\ClassController;

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
            'levels' => LevelResource::collection($this->levels),
            '_links' => [
                'self' => action([ClassController::class, 'show'], $this),
            ]
        ];
    }
}
