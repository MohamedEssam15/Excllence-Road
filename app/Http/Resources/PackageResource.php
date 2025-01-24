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
            'name' => $this->package->translate($this->locale)?->name ?? $this->package->name,
            'description' => $this->package->translate($this->locale)?->description ?? $this->package->description,
            'isJoined' => $this->userEnrollments()->where('user_id', auth()->user()->id ?? null)->exists(),
            'coverPhoto' => $this->package->getCoverPhotoPath(),
            'price' => $this->package->new_price ?? $this->package->price,
            'oldPrice' => $this->package->price,
            'discount' => $this->discount,
            'discountType' => $this->discount_type,
            'startDate' => $this->package->start_date,
            'endDate' => $this->package->end_date,
        ];
    }
}
