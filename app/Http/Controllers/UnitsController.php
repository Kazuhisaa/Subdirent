<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
class UnitsController extends Controller
{
    //


    //Add new Unit
    public function store(Request $request){
     
    $fields = $request->validate([
    'title' => 'required|string|max:255',
    'description' => 'required|string',
    'bedrooms' => 'nullable|integer|min:0',
    'bathrooms' => 'nullable|integer|min:0',
    'floor_area' => 'nullable|integer|min:0',
    'location' => 'required|string|max:255',
    'price' => 'required|numeric|min:0',
]);


$unit = Unit::create([
     'title'=>strip_tags($request->title),
     'description' =>strip_tags($request->description),
     'bedrooms'=>$request->bedrooms,
     'bathrooms'=>$request->bathrooms,
     'floor_area'=>$request->floor_area,
     'location'=> strip_tags($request->location),
     'price'=>strip_tags($request->price)
]);
   

return response()->json([
    'message'=>'unit created',
     'units' =>$unit
]);
      
    }



    public function index()
    {
        $unit = Unit::all();

        return response()->json($unit);
    }

}
