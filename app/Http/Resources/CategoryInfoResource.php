<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryInfoResource extends JsonResource
{
    private $locale;
    public function __construct($category)
    {
        parent::__construct($category);
        $this->locale =App::getLocale();
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "name"=>$this->translate($this->locale)->name,
            "description"=>$this->translate($this->locale)->description,
        ];
    }
}
