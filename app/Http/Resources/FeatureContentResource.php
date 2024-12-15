<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'type' => $this->type,
            'coverPhoto' => $this->getCoverPhotoPath(),
            'coverVideo' => $this->getCoverVideoPath(),
            'modelableType' => $this->modelable_type,
            'modelable' => ($this->modelable_type == 'course')
                ? new CourseBasicInfoResource($this->modelable)
                : ($this->modelable_type == 'package'
                    ? new PackageResource($this->modelable)
                    : null),
        ];
    }
}
