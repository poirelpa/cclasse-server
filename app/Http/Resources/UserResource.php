<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\UserController;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'classes' => ClassResource::collection($this->classes),
            'abilities' => $this->getUserAbilities(),
            'roles' => $this->roles,
            '_links' => [
                //'self' => action([UserController::class, 'show'], $this),
            ]
        ];
    }
}
