<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ContactResource extends JsonResource
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
            'id'=> $this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'address'=> $this->address,
            'avatar'=> Storage::url($this->avatar),
            'numbers'=> NumberResource::collection($this->numbers),
        ];
    }
}
