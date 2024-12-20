<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $categories = category::where(function ($query) use ($term) {
                $query->WhereHas('translations', function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                });
            })->withCount('courses')->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('categories.Partial-Components.all-partial-table', compact('categories'))->render(),
                'pagination' => $categories->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('categories.all-categories');
    }
    public function store(Request $request)
    {
        $request->validate([
            'arCategoryName' => 'required|string',
            'enCategoryName' => 'required|string',
        ]);
        $category = category::create([
            'name' => $request->enCategoryName,
        ]);
        $categoryTranslations = [
            ['locale' => 'ar', 'name' => $request->arCategoryName],
            ['locale' => 'en', 'name' => $request->enCategoryName],
        ];
        $category->translations()->createMany($categoryTranslations);
        return apiResponse(__('response.addedSuccessfully'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'categoryId' => 'required|exists:categories,id',
            'arCategoryName' => 'required|string',
            'enCategoryName' => 'required|string',
        ]);
        $category = category::find($request->categoryId);
        $category->update([
            'name' => $request->enCategoryName,
        ]);
        $categoryTranslations = [
            ['locale' => 'ar', 'name' => $request->arCategoryName],
            ['locale' => 'en', 'name' => $request->enCategoryName],
        ];
        $category->translations()->delete();
        $category->translations()->createMany($categoryTranslations);
        return apiResponse(__('response.updatedSuccessfully'));
    }
}
