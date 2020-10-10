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
    return view('createsymbol');
});
Route::get('/fillpoints', function () {
    $symbol_points = \App\Models\SymbolPoint::all();
    foreach ($symbol_points as $key => $symbol_point) {
        $symbol_point = \App\Models\SymbolPoint::find($symbol_point->id);
        $symbol_point->point = $symbol_point->x.' '.$symbol_point->y;
        $symbol_point->update();
    }
});
Route::get('/test', function () {
    $symbols = Symbol::withCount('points')->get();
    $sum = 0;
    foreach ($symbols as $key => $symbol) {
        $sum += $symbol->points_count;
    }
    $avg_points = round($sum / count($symbols));
    
    $groups = \App\Models\SymbolPoint::orderBy('count', 'desc')
    ->select(DB::raw('point,count(*) as count'))
    ->groupBy('point')
    ->take($avg_points)
    ->get();
        
    return $groups;
});
