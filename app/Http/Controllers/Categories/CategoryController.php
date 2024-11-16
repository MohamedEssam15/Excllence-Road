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
            })->orderBy('updated_at', 'desc')->paginate(10);

            return response()->json([
                'table_data' => view('categories.Partial-Components.all-partial-table', compact('categories'))->render(),
                'pagination' => $categories->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }

        return view('categories.all-categories');
    }
    public function store(Request $request) {}
}
