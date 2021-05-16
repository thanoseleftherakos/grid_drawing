<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symbol;
use App\Models\SymbolPoint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SaveSymbolFormRequest;


class SymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callAction($method, $parameters) //php 8 fix
    {
        return parent::callAction($method, array_values($parameters));
    }

    public function index()
    {
        $symbols = Symbol::select('id','preview')->get();
        return response()->json([
            'success' => true,
            'symbols' => $symbols
        ], 200);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function average()
    {
        
        $symbols = Symbol::withCount('points')->get();
        $sum = 0;
        foreach ($symbols as $key => $symbol) {
            $sum += $symbol->points_count;
        }
        $avg_points = round($sum / count($symbols));
        
        $points = \App\Models\SymbolPoint::orderBy('count', 'desc')
        ->select(DB::raw('point,count(*) as count'))
        ->groupBy('point')
        ->take($avg_points)
        ->get();
        return response()->json([
            'success' => true,
            'points' => $points
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveSymbolFormRequest $request)
    {   
        // $validatedData = $request->validate([
        //     'image' => 'mimetypes:image/jpeg,image/bmp,image/png',
        //     'symbol_category_id' => 'required'
        //     // 'data.points' => 'required',
        // ],[],[
        //     'symbol_category_id' => 'Symbol Category'
        // ]);
        $data = json_decode($request['data']);
        if($data->symbol_id) {
            $symbol = Symbol::findOrFail($data->symbol_id);
        } else {
            $symbol = new Symbol;
        }
        $symbol->position_x = $data->img_position->x;
        $symbol->position_y = $data->img_position->y;
        $symbol->scale = $data->img_position->scale;
        $symbol->rotate = $data->img_position->rotate;
        $symbol->symbol_category_id = $data->symbol_category_id;
        if($request->has('image')) {
            $img_contents = file_get_contents($request->image->path());
            $original_name = strtolower(str_replace(' ', '', $request->image->getClientOriginalName()));
            $file_name = time().rand(100,999).$original_name;
            $path = Storage::disk('public')->putFile('/scans', $request->image, 'public');
            $symbol->image = $path;
        }
        if( isset($data->preview_image) ){
            $img_data = substr($data->preview_image, strpos($data->preview_image, ',') + 1);
            $img_data = base64_decode($img_data);
            $filename = time().rand(100,999).".png";
            $saved = Storage::disk('public')->put($filename, $img_data);
            Storage::disk('public')->delete($symbol->preview);
            if($saved) {
                $symbol->preview = $filename;
            }
        }
        if($data->symbol_id) {
            $symbol->update();
        } else {
            $symbol->save();
        }
        
        if( isset($data->points) ){
            if($data->symbol_id) {
                $symbol->points()->delete();
            }
            foreach ($data->points as $key => $point) {
                $symbol_point = new SymbolPoint;
                $symbol_point->x = $point[0];
                $symbol_point->y = $point[1];
                $symbol_point->point = $point[0] . ' ' . $point[1];
                $symbol_point->symbol_id = $symbol->id;
                $symbol_point->save();
            }
        }
        return response()->json([
            'success' => true,
            'message' => $data->symbol_id ? 'Symbol updated successfully! 😄' : 'Symbol saved successfully! 😄',
            'symbol_id' => $symbol->id
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $symbol = Symbol::with('points')->findOrFail($id);
        return response()->json([
            'success' => true,
            'symbol' => $symbol
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
