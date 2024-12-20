<?php

namespace App\Http\Controllers;

use App\Models\ContactUsMessage;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->get('query') ?? '';

        if ($request->ajax()) {
            $contacts = ContactUsMessage::where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('email', 'LIKE', '%' . $term . '%')
                    ->orWhere('phone', 'LIKE', '%' . $term . '%')
                    ->orWhere('message', 'LIKE', '%' . $term . '%');
            })->orderBy('updated_at', 'desc')->paginate(10);
            return response()->json([
                'table_data' => view('contact-us.Partial-Components.all-partial-table', compact('contacts'))->render(),
                'pagination' => $contacts->links('vendor.pagination.bootstrap-5')->render()
            ]);
        }
        return view('contact-us.all');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'message' => 'required|string|max:255',
        ]);
        $contact = ContactUsMessage::create($request->all());
        return apiResponse(__('response.addedSuccessfully'));
    }
}
