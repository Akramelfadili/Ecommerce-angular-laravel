<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
        public function add(Request $request){
            $validate  = Validator::make($request->all(),[
                "name" => "required",
                "category" => "required",
                "brand" => "required",
                "description" => "required",
                "price" => "required",
                "image" => "required|image",
            ]);

            if($validate->fails()){
                return response()->json(['error'=> $validate->errors()->all()],409);
            }

            $product = new Product();
            $product->name = $request->name;
            $product->category = $request->category;
            $product->brand = $request->brand;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->save();

            //file
            $url = "http:localhost:8000/storage/";
            $file = $request->file("image");
            $extension = $file -> getClientOriginalExtension();
            $path = $request->file("image")->storeAs("proimages", $product->id.".".$extension);
            $product->image = $path;
            $product-> imagePath = $url.$path;
            $product->save();

            return response()->json(["message"=> "Product has been successfully added"]); 
        }

    public function update(Request $request){   
        $validate  = Validator::make($request->all(),[
            "name" => "required",
            "category" => "required",
            "brand" => "required",
            "description" => "required",
            "id" => "required",
            "price" => "required",
         
        ]);

        if($validate->fails()){
            return response()->json(['error'=>$validate->errors()->all()],409);
        }
        
        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->category = $request->category;
        $product->brand = $request->brand;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();
 
        return response()->json(["message"=> "Product has been successfully updated"]);
    }

    public function delete(Request $request){
        $validate  = Validator::make($request->all(),[
            "id" => "required",     
        ]);

        if($validate->fails()){
            return response()->json(['error'=>$validate->errors()->all()],409);
        }
        
        $product = Product::find($request->id)->delete();
        return response()->json(["message"=> "Product has been successfully deleted"]);
    }

    public function show(Request $request){
        session(["keys"=> $request->keys]);

        $products = Product::where(function ($q){
            $q->where("products.id",'LIKE','%'.session("keys").'%')
                ->orwhere("products.name",'LIKE','%'.session("keys").'%')
                ->orwhere("products.price",'LIKE','%'.session("keys").'%')
                ->orwhere("products.category",'LIKE','%'.session("keys").'%')
                ->orwhere("products.brand",'LIKE','%'.session("keys").'%');
        })->select("products.*")->get();

        return response()->json(["products"=>$products]);
    }
    
}
