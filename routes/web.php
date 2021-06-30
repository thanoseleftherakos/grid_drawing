<?php

use Illuminate\Support\Facades\Route;
use App\Models\Symbol;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    $symbol_categories = \App\Models\SymbolCategory::select('id','name')->get();
    return view('createsymbol')->with('symbol_categories',$symbol_categories);
});


Route::get('/pallimpsest', function () {
    $symbol = \App\Models\Symbol::where('symbol_category_id', 3)->with('points')->inRandomOrder()->first();
    return view('pallimpsest')->with('symbol',$symbol);
});
