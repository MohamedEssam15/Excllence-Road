<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryResource extends JsonResource
{

    private $locale;
    private $category;
    public function __construct($category)
    {
        parent::__construct($category);
        $this->locale = App::getLocale();
        $this->category = $category;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->category->id,
            "name" => $this->category->translate($this->locale)?->name ?? $this->category->name,
            "description" => $this->category->translate($this->locale)?->description ?? $this->category->description,
            "coursesCount" => $this->category->courses_count,
        ];
    }
}
