<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symbol;
use App\Models\SymbolPoint;
use Illuminate\Support\Facades\Storage;

class SymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'mimetypes:image/jpeg,image/bmp,image/png',
            // 'data.points' => 'required',
        ]);
        $data = json_decode($request['data']);
        if($data->symbol_id) {
            $symbol = Symbol::findOrFail($data->symbol_id);
        } else {
            $symbol = new Symbol;
        }
        if($request->has('image')) {
            $img_contents = file_get_contents($request->image->path());
            $original_name = strtolower(str_replace(' ', '', $request->image->getClientOriginalName()));
            $file_name = time().rand(100,999).$original_name;
            $path = Storage::putFile('scans', $request->image, 'public');
            
            $symbol->position_x = $data->img_position->x;
            $symbol->position_y = $data->img_position->y;
            $symbol->scale = $data->img_position->scale;
            $symbol->rotate = $data->img_position->rotate;
            $symbol->image = $path;
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
        //
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
