<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'password' => $this->password,
            'email' => $this->email,
            'number_phone' => $this->number_phone,
            'roles' => $this->roles,
            'photo_profile' => $this->photo_profile ? Storage::url($this->photo_profile) : 'https://fakeimg.pl/100x100/?text-Book&font-noto',
        ];
    }
}
