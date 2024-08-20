<?php

namespace App\Http\Controllers;
use App\Models\CustomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Unit; 
use App\Models\Location;
use App\Models\image;
use App\Models\amenities;
use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();

        if ($user->count() != 0){
            return response()->json([
                'status'=>200,
                'data'=>$user
            ]);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>'user table empty!'
            ]);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Log the entire request data to debug
        \Log::info('Request Data:', $request->all());
    
        $data = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string',
            'password' => 'required|string|min:8',
            'is_admin' => 'required|boolean',
            'profile_photo' => 'nullable|string', // Adjusted to allow Base64 string
            'description' => 'nullable|string|min:3',
            'general_property_overview' => 'nullable|string|min:3'
        ]);
    
        if ($data->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to create user',
                'errors' => $data->errors(),
            ], 400);
        }
    
        $validatedData = $data->validated();
    
        // Base path gives the root directory of the Laravel Project. We are targeting the Angular directory
        // $directoryPath = base_path('practice with Devextrem/src/assets/images/user/userProfile');
    
        // // Check if the directory exists, if not, create it with permission 0755
        // if (!\File::exists($directoryPath)) {
        //     \File::makeDirectory($directoryPath, 0755, true);
        // }
    
        // Handling profile photo if it's provided as a Base64 string
        // $profilePhotoPath = null;

        
        if (!empty($validatedData['profile_photo'])) {
            // Decode the Base64 string and save it as an image
            $image = $validatedData['profile_photo'];
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
        
            // Image saved with a file name that includes the current timestamp for uniqueness
            $profilePhotoName = time() . '_profile_photo.jpg';
        
            // Define the directory path where the image will be stored (directly in storage/app/public)
            $directoryPath = ''; // Empty string means root of 'storage/app/public'
        
            // Store the image in the specified directory within the 'public' disk
            Storage::disk('local')->put($profilePhotoName, base64_decode($image));
        
            // Generate the public URL to access the profile photo
            $path = Storage::url($profilePhotoName);
        
            // Save $path to the database
        }
        
        
        // Correctly handling the boolean conversion for is_admin
        $isAdmin = filter_var($validatedData['is_admin'], FILTER_VALIDATE_BOOLEAN);
        
        // Create the user with the validated data
        $created_user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'password' => $validatedData['password'],
            'is_admin' => $isAdmin,
            'profile_photo' => $path ?? 'Images/user/userProfile/profilePhoto.webp', // Use default if not provided
            'description' => $validatedData['description'] ?? 'The user is a dedicated and passionate homeowner who has meticulously curated a selection of premium properties available for rent and sale. With a keen eye for detail and a commitment to providing exceptional living experiences, the user has become a trusted name in the real estate community. His properties are known for their modern amenities, prime locations, and impeccable upkeep.',
            'general_property_overview' => $validatedData['general_property_overview'] ?? 'The user\'s portfolio includes a diverse range of properties, from cozy apartments to spacious family homes. Each property is carefully maintained and equipped with all the necessary amenities to ensure comfort and convenience for tenants. The user prides himself on offering homes that are not just places to live, but environments where people can truly thrive.'
        ]);
        
        if ($created_user) {
            return response()->json([
                'status' => 201,
                'message' => 'User Created',
                'user' => $created_user
            ], 201);
        }
    
        return response()->json([
            'status' => 500,
            'message' => 'User creation failed',
        ], 500);
    }
                 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if ($user){
            return response()->json([
                'status'=>200,
                'user'=> $user
            ], 200);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'user not found',
                'errors'=>$user->errors()
            ], 404);
        }

        return response()->json([
            '500'=>'Custom Error message!'
        ]);
        
    }

    // For displying unit data
    public function profileDetails(Request $request)
    {
        $username = $request->header('username'); // gets the username for query set
        $user = User::where('name', $username)->first(); // gets the first occurrence of the name sent
    
        if ($user) {
            // Gets all the units belonging to that user
            $units = Unit::where('user_id', $user->id)->get();
    
            // Initialize arrays for storing related data
            $unitDetails = [];
            
            // Loop through units to get related rows of foreign keys
            foreach ($units as $unit) {
                $location = Location::where('unit_id', $unit->id)->first();
                $images = Image::where('unit_id', $unit->id)->get();
                $amenities = Amenities::where('unit_id', $unit->id)->first();
    
                $unitDetails[] = [
                    'unit' => $unit,
                    'location' => $location,
                    'images' => $images,
                    'amenities' => $amenities
                ];
            }
    
            return response()->json([
                'status' => 200,
                'user' => $user,
                'units' => $unitDetails
            ]);
    
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'User not found!'
            ], 404);
        }
    }
    
    public function signin(Request $request){
        $username = $request->username;
        $password = $request->password;
    
        $user = User::where('name', $username)->first();
    
        if ($user && ($password === $user->password)){
            return response()->json([
                'status' => 200,
                'message' => 'Success'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid credentials',
                'issue'=>$user
            ]);
        }
    }
    
          /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $user = User::find($id);

    if ($user) {
        $data = Validator::make($request->all(), [

            'name' => 'required|string|min:3|max:30',
            'email' => 'required|email|unique:users,email,' . $id, // email will be unique except for the current user's email
            'phone_number'=>'',
            'password' => 'required|string|min:8',
            'is_admin' => 'required|boolean',
            'profile_photo' =>'',
            'description'=>'string|min:3',
            'general_property_overview'=>'string|min:3'

        ]);

        if ($data->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to validate!',
                'errors' => $data->errors()
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number'=>$request->phone_number,
                'password' => $request->password,
                'is_admin' =>  $request->is_admin,
                'profile_photo'=>$request->profile_photo,
                'description'=>$request->description,
                'general_property_overview'=>$request->general_property_overview,
            ]);

            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        }
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'User not found!'
        ]);
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user){
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Deleted'
            ]);

        }else{
            return response()-json([
                'status' => 400,
                'message' => 'Faild'
            ]);
        }
    }
}
