<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=Product::latest()->get();
        if($data->count()> 0){
              return response()->json(["data"=>$data,"status"=> Response::HTTP_OK],Response::HTTP_OK);
    }else{
        return response()->json(["data"=>"there is no data","status"=> Response::HTTP_NO_CONTENT],Response::HTTP_OK);

    }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "name"=> "required|string",
            "price"=> "required|numeric",
            "description"=> "required|string",
            "image"=> "required|file|mimes:png,jpg,svg,jpeg",
        ]);
        if($validator->fails()){
            return response()->json(["data"=>$validator->errors()],Response::HTTP_FAILED_DEPENDENCY);
        }else{
            $validator=$validator->validated();
            //image handle
            $file=$request->file("image");
            $filename=time().'.'.$file->getClientOriginalExtension();
            $validator["image"]= "storage".$filename;
            $file->move(public_path('storage'), $filename);
            // end of image handle
            
            $product=Product::create($request->all());
            
            return response()->json(["data"=>$product,"status"=> Response::HTTP_OK],Response::HTTP_OK);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data=Product::find($id);
        if($data->count()> 0){
              return response()->json(["data"=>$data,"status"=> "sucsess","msg"=>"done"],Response::HTTP_OK);
    }else{
        return response()->json(["data"=>"there is no data","status"=> Response::HTTP_NOT_FOUND],Response::HTTP_OK);

    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $data=Product::find($id);

        $validator=Validator::make($request->all(),[
            "name"=> "required|string",
            "price"=> "required|numeric",
            "description"=> "required|string",
            "image"=> "required|file|mimes:png,jpg,svg,jpeg",
        ]);
        if($validator->fails()){
            return response()->json(["data"=>$validator->errors()],Response::HTTP_BAD_REQUEST);
        }else{
            $validator=$validator->validated();
            //image handle
            $file=$request->file("image");
            $filename=time().'.'.$file->getClientOriginalExtension();
            $validator["image"]='/public'.$filename;
            $file->move(public_path('/public'), $filename);
            // end of image handle
            $product=$validator->update($data);
            return $validator;
            // return response()->json(["data"=>$product,"status"=> Response::HTTP_OK],Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data=Product::find($id);
        if($data->count()> 0){
            $data->delete();
              return response()->json(["data"=>$data,"status"=> "sucsess","msg"=>"done"],Response::HTTP_OK);
    }else{
        return response()->json(["data"=>"there is no data","status"=> Response::HTTP_NOT_FOUND],Response::HTTP_OK);

    }
    }
}
