<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'submid_date' => $this->submid_date,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'number_phone' => $this->user->number_phone,
            ],
            'proyek' => $this->proyek->map(fn ($item) => [
                'id' => $item->id,
                'name_proyek' => $item->name_proyek,
                'description' => $item->description,
            ]),
        ];
    }
}
