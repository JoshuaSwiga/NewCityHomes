<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\image;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class unitImage extends Controller
{
    public function indexImage(){
        $imageData = image::all();

        if ($imageData->count() > 0){
            return response()->json([
                'status'=>200,
                'data'=>$imageData
            ]);
        }
    }

    public function editImage(Request $request, int $id){
        $selectedImage = image::find($id);

        if ($selectedImage){
            $selectedImage->update($request->all());

            if ($selectedImage){
                return response()->json([
                    'status'=>200,
                    'message'=>'Image updated!'
                ]);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Image Not Updated'
                ]);
            }
        }else{
            return response()->json([
                'status'=>400,
                'message'=>'Image Not Found!'
            ]);
        }
    }

    public function deleteImage(int $id){
        $imageData = image::find($id);

        if ($imageData){
            $attempt=$imageData->delete();
            if ($attempt){
                return response()->json([
                    'status'=>200,
                    'message'=>'Image deleted'
                ]);
            }
        }
    }

    // Check on this...
    public function addImage(Request $request) {
    
        $userName = $request->header('username');
        $unitId = $request->header('unitId');
    
        // Validate image field
        $validation_attempt = Validator::make($request->all(), [
            'image' => '',
        ]);
    
        if ($validation_attempt->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation Failed!',
                'errors' => $validation_attempt->errors()
            ], 400);
        }
    
        $userData = User::where('name', $userName)->first();
    
        if (!$userData) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found!'
            ], 404);
        }
    
        if (!$unitId) {
            return response()->json([
                'status' => 404,
                'message' => 'Unit ID not provided!'
            ], 404);
        }
    
        // Handle the image upload
        if ($request->image) { 
            // if ($request->hasFile('image')) {
            // $image = $request->file('image');
            // $imageName = time() . '_' . $image->getClientOriginalName();
            // $imagePath = $image->move(public_path('Images'), $imageName);
    
            // Save image path to the database
            $saveImageAttempt = Image::create([
                'image' => $request->image,
                'user_id' => $userData->id,
                'unit_id' => $unitId ?? 1

                // 'image' => 'Images/' . $imageName,
                // 'user_id' => $userData->id,
                // 'unit_id' => $unitId
            ]);

            return response()->json([
                'status'=>201,
                'message'=>'Test image saved'
            ]);
        }
    
        //     if ($saveImageAttempt) {
        //         return response()->json([
        //             'status' => 201,
        //             'message' => 'Image saved successfully!',
        //             'image_path' => 'Images/' . $imageName
        //         ]);
        //     } else {
        //         return response()->json([
        //             'status' => 500,
        //             'message' => 'Failed to save image!'
        //         ]);
        //     }
        // } else {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'No image file found!'
        //     ], 400);
        }
    }

