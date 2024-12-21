<?php

namespace App\Http\Controllers\Packages;

use App\Enum\DiscountTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddDiscountPackageRequest;
use App\Http\Requests\AddPackageRequest;
use App\Models\category;
use App\Models\Course;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackagesController extends Controller
{
    public function activePackages(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $packages = Package::where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->whereDate('start_date', '>=', today())->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('packages.Partial-Components.active-package-partial-table', compact('packages'))->render(),
                'pagination' => $packages->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }


        return view('packages.active-packages');
    }

    public function inProgressPackages(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $packages = Package::where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->whereDate('start_date', '<=', today())->whereDate('end_date', '>=', today())->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('packages.Partial-Components.in-progress-package-partial-table', compact('packages'))->render(),
                'pagination' => $packages->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }


        return view('packages.in-progress-packages');
    }

    public function expiredPackages(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $packages = Package::where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->whereDate('end_date', '<', today())->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('packages.Partial-Components.expired-package-partial-table', compact('packages'))->render(),
                'pagination' => $packages->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('packages.expired-packages');
    }

    public function create(Request $request)
    {

        return view('packages.add-package');
    }

    public function store(AddPackageRequest $request)
    {
        $image = $request->coverPhoto;
        $imageType = $image->getClientOriginalExtension();
        $imageName = Str::random(10) . '.' . $imageType;
        $package = Package::create([
            'name' => $request->enName,
            'description' => $request->enDescription,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'price' => $request->price,
            'cover_photo' => $imageName,
            'is_popular' => !is_null($request->addToPopularPackages),

        ]);
        $path = "/packages_attachments/{$package->id}/cover_photo/";
        $image->storeAs($path, $imageName, 'publicFolder');
        $translations = [
            ['locale' => 'en', 'name' => ucfirst($package->name), 'description' => $package->description],
            ['locale' => 'ar', 'name' => $request->arName, 'description' => $request->arDescription],
        ];

        $package->courses()->sync($request->courses);
        $package->translations()->createMany($translations);

        return to_route('packages.active')->with('status', __('response.addedSuccessfully'));
    }

    public function edit(Package $package)
    {
        return view('packages.edit-package', compact('package'));
    }
    public function update(Request $request, Package $package)
    {
        $package->name = $request->enName;
        $package->description = $request->enDescription;
        $package->start_date = $request->startDate;
        $package->end_date = $request->endDate;
        $package->price = $request->price;
        $package->is_popular = !is_null($request->addToPopularPackages);
        if ($request->coverPhoto != null) {
            $deletePath = "/packages_attachments/{$package->id}/cover_photo/{$package->cover_photo}";
            Storage::disk('publicFolder')->delete($deletePath);
            $image = $request->coverPhoto;
            $imageType = $image->getClientOriginalExtension();
            $imageName = Str::random(10) . '.' . $imageType;
            $path = "/packages_attachments/{$package->id}/cover_photo/";
            $image->storeAs($path, $imageName, 'publicFolder');
            $package->cover_photo = $imageName;
        }
        $package->save();
        $translations = [
            ['locale' => 'en', 'name' => ucfirst($request->enName), 'description' => $request->enDescription],
            ['locale' => 'ar', 'name' => $request->arName, 'description' => $request->arDescription],
        ];
        $package->courses()->sync($request->courses);
        $package->translations()->delete();
        $package->translations()->createMany($translations);


        return to_route('packages.active')->with('status', __('response.updatedSuccessfully'));
    }
    public function getCategories()
    {
        $categoriesQuery = category::all();
        $categories = [];
        foreach ($categoriesQuery as $category) {
            $categories[] = [
                'id' => $category->id,
                'name' => $category->translate(App::getLocale())->name,
            ];
        }
        return response()->json($categories);
    }

    public function show(Package $package)
    {

        return view('packages.show-package', compact('package'));
    }

    public function getCoursesByCategoryId($categoryId)
    {
        // Fetch courses where the category_id matches the selected ID
        $coursesQuery = Course::where('category_id', $categoryId)->get();
        $courses = [];
        foreach ($coursesQuery as $course) {
            $courses[] = [
                'id' => $course->id,
                'name' => $course->translate(App::getLocale())->name,
            ];
        }
        return response()->json($courses);
    }

    public function addDiscount(AddDiscountPackageRequest $request)
    {
        $package = Package::find($request->packageId);
        $package->discount = $request->discount;
        $package->discount_type = $request->discountType;
        if ($request->discountType == DiscountTypes::FIXED) {
            $package->new_price = $request->discount;
        } else {
            $package->new_price = $package->price - (($package->price * $request->discount) / 100);
        }
        $package->save();
        return apiResponse(__('translation.discountAdded'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyDiscount(Request $request)
    {
        $this->validate($request, [
            'packageId' => 'required|exists:packages,id',
        ]);
        $package = Package::find($request->packageId);
        $package->discount = null;
        $package->discount_type = null;
        $package->new_price = null;
        $package->save();
        return apiResponse(__('translation.discountRemoved'));
    }
}
