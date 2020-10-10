<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SymbolCategory;

class SymbolCategoryController extends Controller
{
    public function index()
    {
        $symbol_categories = SymbolCategory::select('id','name')->get();
        return response()->json([
            'success' => true,
            'symbols' => $symbol_categories
        ], 200);   
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);
        $data = json_decode($request['data']);
        $category = SymbolCategory::create([
            'name' => $data->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Symbol Category created successfully! 😄',
            'categories' => SymbolCategory::select('id','name')->get()
        ], 200);

    }
}
