<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Query;
use Illuminate\Support\Facades\Validator;


class userQueries extends Controller
{
    // Required Authentication
    public function index(){
        $allQueires = Query::all();

        if ($allQueires->count() > 0){
            return response()->json([
                'status'=>200,
                'message'=>$allQueires
            ]);
        } else {
            return response()->json([
                'status'=>200,
                'message'=>'Sucessfull request. However, no queries available!'
            ]);
        }

    }


    // Required Authentication
    public function delete(int $id){
        $query = Query::find($id);
        if ($query){
            $query->delete();

            if ($query){
                return response()->json([
                    'status'=>200,
                    'message'=>'Query deleted!'
                ]);
            }
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Faild To Delete Query!'
            ]);
        }
    }

    // Can be done by the user
    public function addQuery(Request $request){
        $validation = Validator::make($request->all(), [
            'name'=>'required|string',
            'phone_number'=>'required|string',
            'email'=>'required|string',
            'category'=>'',
            'priceRange'=>'required|string',
            'evntType'=>'required|string',
            'additionalServiceRequirenent'=>'required|string',
        ]);

        if ($validation->fails()){
            return response()->json([
                'status'=>404,
                'message'=>'Faild to process request'
            ]);
        }else{
            $savedData = Query::create([
                'name'=>$request->name,
                'phone_number'=>$request->phone_number,
                'email'=>$request->email,
                'category'=>$request->category,
                'priceRange'=>$request->priceRange,
                'evntType'=>$request->evntType,
                'additionalServiceRequirenent'=>$request->additionalServiceRequirenent
            ]);

            if ($savedData){
                return response()->json([
                    'status'=>201,
                    'message'=>'Query created sucessfully!'
                ]);
            }
        }
    }

}
