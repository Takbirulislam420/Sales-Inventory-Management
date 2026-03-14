<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class UserManageController extends Controller
{
    public function createUser(Request $request){
        $validator= Validator::make($request->all(),[
            'name'=> 'required',
            'email' => 'required|email',
            'password'=>'required|min:6',
            'phone' => 'required',
            'roles' => 'required|array',
            'roles.*'=>'exists:roles,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> 'error',
                'message' => 'Validation erroe',
                'error' => $validator->error(),
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
