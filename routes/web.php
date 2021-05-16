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

Route::get('/php', function () {
    return phpinfo();
});

Route::get('/addcat', function () { 
    $symbols = \App\Models\Symbol::all();
    foreach ($symbols as $key => $symbol) {
        $symbol = \App\Models\Symbol::find($symbol->id);
        $symbol->symbol_category_id = 1;
        $symbol->update();
    }
    return 'ok';
});

