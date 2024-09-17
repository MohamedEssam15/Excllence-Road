<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherInfoResource extends JsonResource
{

    public function __construct($teacher)
    {
        parent::__construct($teacher);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'role' => $this->userRole(),
            'avatar'=>$this->getAvatarPath(),
        ];
    }

    protected function userRole()
    {
        return is_null($this->roles()) ? null : $this->roles->implode('name', ',');
    }
}
