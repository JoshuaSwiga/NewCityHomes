<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;


class LocationController extends Controller
{
    public function index(){

        $data = Location::all();

        if ($data->count() !== 0){
            return response()->json([
                'status'=>200,
                'message'=>$data
            ]);
        }
        
    }

    public function delete_location(int $id){
        $location = Location::find($id);

        if (!$location){
            return response()->json([
                'status'=>404,
                'message'=>'Location Not Found!'
            ]);

        }else{
            $location->delete();

            return response()->json([
                'status'=>200,
                'message'=>'Removed!',
                'removed-data'=>$location
            ]);
        }
    }

    // Review Logic
    public function edit_location(Request $request, int $id) {
        
        $location = Location::find($id);
        
        if ($location) {
            $validated = Validator::make($request->all(), [
                'city' => 'required|min:1|string',
                'state' => 'required|min:1|string',
                'country' => 'required|min:1|string',
                'unit_id'=>'required'
            ]);
    
            if ($validated->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validated->errors()
                ]);
            } else {
                $location->update(
                    $request->all()
                );
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Updated',
                    'new-location' => $location
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Location Not Found'
            ]);
        }
    }
    
    public function each_location(int $id) {
        $each_location = Location::find($id);
        
        if ($each_location) {
            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => $each_location,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Location Not Found!'
            ]);
        }
    }
    

    public function add_location(Request $request){
        $unit = $request->header('unit_id');

        if ($unit){

        
            $validator = Validator::make($request->all(), [
                'city' => 'required|min:1|string',
                'state' => 'required|min:1|string',
                'country' => 'required|min:1|string',
                'unit_id'=>'required'
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Validation Failed',
                    'errors' => $validator->errors()
                ], 400);
            } 
        
            $saved_data = Location::create([
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'unit_id'=>$unit->id
            ]);
        
            if ($saved_data) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Success! Location Saved',
                    'data'=>$saved_data
                ], 201);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Server Error. Location not saved.'
                ], 500);
            }
        }
    }    
}
