<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "email"=>$this->email,
            "avatar"=>$this->getAvatarPath(),
            "role"=>$this->userRole(),
            "verfiedAt"=>$this->email_verified_at?->format('Y-m-d'),
        ];
    }
    protected function userRole()
    {
        return is_null($this->roles()) ? null : $this->roles->implode('name', ',');
    }
}
