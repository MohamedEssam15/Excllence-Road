<?php

namespace App\Http\Resources;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class StudentCoursesResource extends JsonResource
{
    protected $locale;

    public function __construct($course)
    {
        parent::__construct($course);
        $this->locale = App::getLocale();
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translate($this->locale)->name,
            'description' => $this->translate($this->locale)->description,
            'coverPhoto' => $this->getCoverPhotoPath(),
            'fromPackage' => $this->pivot->from_package,
            'package' => $this->pivot->from_package ? $this->getPackageInfo($this->pivot->package_id) : null
        ];
    }

    private function getPackageInfo($packageId)
    {
        $package = Package::find($packageId);
        return new PackageResource($package);
    }
}
