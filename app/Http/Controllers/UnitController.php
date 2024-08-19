<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Unit;
use App\Models\User;
use App\Models\Location;
use App\Models\image;
use App\Models\amenities;

use Illuminate\Support\Facades\Validator;
 
class UnitController extends Controller
{
    public function index() {
        // Use Eloquent relationships to load related data
        $units = Unit::with(['Location', 'user', 'images'])->get();
        
        // Initialize arrays to store related data
        $locationData = [];
        $amenityData = [];
        $imageData = [];
        $userData = [];
    
        // Loop through each unit to get related data
        foreach ($units as $unit) {
            $location = Location::where('unit_id', $unit->id)->first();
            $amenity = Amenities::where('unit_id', $unit->id)->first();
            $images = Image::where('unit_id', $unit->id)->get();
            $user = User::where('id', $unit->user_id)->first();
    
            // Store related data in arrays
            
            $locationData[] = $location;
            $amenityData[] = $amenity;
            $imageData[] = $images;
            $userData[] = $user;
        }
        $data=[
            'units'=>$units,
            'location'=>$locationData,
            'amenities'=>$amenityData,
            'images'=>$imageData,
            'userData'=>$userData
        ];
    
        return response()->json([
            'status' => 200,
            'units' => $data,
            // 'locationData' => $locationData,
            // 'amenityData' => $amenityData,
            // 'imageData' => $imageData,
            // 'userData' => $userData
        ]);
    }
    
    public function add_unit(Request $request)
    {
        // Getting username from headers sent
        $user_name = $request->header('username');
    
        // Getting User Details
        $user = User::where('name', $user_name)->first();
    
        // Checks if user exists for authentication
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found!',
            ], 404);
        }
    
        // Create unit
        $unit = Unit::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'user_id' => $user->id, // Correctly accessing the user's id
            'userThatUploaded' => $user->name,
            'category' => $request->category,
            'accomodation_information' => $request->accomodation_information,
            'number_of_bedrooms' => $request->number_of_bedrooms,
            'number_of_bathrooms' => $request->number_of_bathrooms,
            'price_information' => $request->price_information
        ]);
    
        // Create location
        $location = Location::create([
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'unit_id' => $unit->id
        ]);
    
        // Save amenities if submitted
        $amenities = null;
        if ($request->amenities) {
            $amenities = Amenities::create([
                'amenities' => $request->amenities,
                'unit_id' => $unit->id
            ]);
        }
    
        // Create image and associate with unit
        $image = Image::create([
            'image' => $request->image,
            'user_id' => $user->id,
            'unit_id' => $unit->id
        ]);
    
        // Return response when created
        if ($unit && $location && $image) {
            return response()->json([
                'status' => 200,
                'message' => 'Unit created successfully!',
                'unit' => $unit,
                'location' => $location,
                'image' => $image,
                'amenities' => $amenities ?? "Was Not Added!"
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Unit creation failed due to an internal error.'
            ], 500);
        }
    }
           
    public function updateUnitDetails(Request $request, $id)
{
    $unit = Unit::find($id);
    if (!$unit) {
        return response()->json([
            'status' => 404,
            'message' => 'Unit not found'
        ], 404);
    }

    $validation_attempt = Validator::make($request->all(), [
        'title' => 'required|min:5|string',
        'subtitle' => 'required|min:1|string',
        'category' => 'required|min:1|string',
        'accomodation_information' => 'string|min:1',
        'number_of_bedrooms' => 'string|min:1',
        'number_of_bathrooms' => 'string',
        'price_information' => '',
    ]);

    if ($validation_attempt->fails()) {
        return response()->json([
            'status' => 400,
            'message' => 'Validation Failed!',
            'errors' => $validation_attempt->errors()
        ], 400);
    }

    $unit->update($request->all());

    return response()->json([
        'status' => 200,
        'message' => 'Unit updated successfully',
        'unit' => $unit
    ]);
}

    
    // TODO: Test
    public function delete_unit(int $id) {
        $unit = Unit::find($id);
        if ($unit){
            $unit->delete();

            if ($unit){
                return response()->json([
                    'status'=>200,
                    'message'=>'Unit Removed!'
                ]);
            }else{
                return response()->json([
                    'status'=>400,
                    'message'=>'Not deleted!'
                ]);
            }

        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Unit not found!'
            ]);
        }
    }

    public function view_each_unit(int $id){
        $unit = Unit::find($id);
    
        if ($unit) {
            $user = User::find($unit->user_id);
            $location = Location::where('unit_id', $unit->id)->first();
            $images = image::where('unit_id', $unit->id)->get();
            $amenity = amenities::where('unit_id', $unit->id)->first();

    
            return response()->json([
                'status' => 200,
                'unit' => $unit,
                'location' => $location,
                'user' => $user,
                'image' => $images,
                'amenity'=>$amenity
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Unit not found',
            ]);
        }
    }
    
}
