<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = category::withCount(['courses' => function ($query) {
            $query->whereHas('status', function ($statusQuery) {
                $statusQuery->where('name', 'active'); // Assuming 'name' column holds 'active' status
            })->where('start_date', '>', Carbon::today());
        }])->get();

        if (!isset($categories[0])) {
            return apiResponse('No Categories Available', new stdClass(), ['No Categories Available'], 404);
        }

        return apiResponse('Data Retrieved', CategoryResource::collection($categories));
    }
}
