<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request){
        $validate  = Validator::make($request->all(),[
            "email" => "required|unique:users",
            "password" => "required",
            "name" => "required",
        ]);

        if($validate->fails()){
            return response()->json(['error'=>$validate->errors()->all()],409);
        }

        $product = new User();
        $product->name = $request->name;
        $product->email = $request->email;
        $product->password = encrypt($request->password);
        $product->save();

        return response()->json(['message'=> "Successafly registered");
    }

    public function login(Request $request){
        $validate  = Validator::make($request->all(),[
            "email" => "required",
            "password" => "required",
        ]);

        
        if($validate->fails()){
            return response()->json(['error'=>$validate->errors()->all()],409);
        } 

        $user = User::where("email",$request->email)->get()->first();
        $password = decrypt($user->password);

        if($user && $password == $request->password){
            return response()->json(['user  '=>$user);
        }else{
            return response()->json(["error"=>["Something went wrong"]],409),;
        }
    }

}
