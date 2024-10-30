<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class PackageResource extends JsonResource
{
    private $locale;
    private $package;
    public function __construct($package)
    {
        parent::__construct($package);
        $this->locale = App::getLocale();
        $this->package = $package;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->package->id,
            'name' => $this->package->translate($this->locale)->name,
            'description' => $this->package->translate($this->locale)->description,
            'coverPhoto' => $this->package->getCoverPhotoPath(),
            'price' => $this->package->price,
            'startDate' => $this->package->start_date,
            'endDate' => $this->package->end_date,
        ];
    }
}
