<?php

namespace App\Http\Resources;

use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'subjects' => SubjectResource::collection($this->whenLoaded('subjects')),
            '_links' => [
                'self' => action([ProgramController::class, 'show'], $this),
                'reference' => Storage::disk('public')->url('references/' . $this->reference_file)
            ]
        ];
    }
}
