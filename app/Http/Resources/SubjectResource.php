<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'reference_xpath' => $this->reference_xpath,
            'children' => SubjectResource::collection($this->children),
            '_links' => [
                'reference' => Storage::disk('public')->url('references/'.$this->program->reference_file)
            ]
        ];
    }
}
