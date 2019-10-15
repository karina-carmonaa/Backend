<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                //CREATE-2
            'name' => 'required',
            //CREATE-3, CREATE-4, CREATE-5
            'price' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                        'code' => 'ERROR-1',
                        'title' => 'UnprocessableEntity'
                    ]], 422);
        }
        // Create a new product
        //CREATE-1
     $product = Product::create($request->all());
     return response()->json($product,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function showProduct(String $id)
    {
        $producto = Product::find($id);
         if($producto){
            //SHOW-1
            return response()->json($producto,200);
        }
        else {
            //SHOW-2
            return response()->json([
            'errors' => [
                'code' => 'ERROR-2',
                'title' => 'Not Found'
                ]], 404);
        }
    }
	
		/**
     * Display all resources.
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        //LIST1, LIST-2
        $product = Product::all();
        return response()->json($product,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $id
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, String $id)
    {
        $validator = Validator::make($request->all(), [
            // UPDATE-2, UPDATE-3
            'price' => 'numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'code' => 'ERROR-1',
                    'title' => 'UnprocessableEntity'
                    ]], 422);
        }
        $product = Product::find($id);
        if($product){
            //UPDATE-1
            $product->name = $request->name;
            $product->price = $request->price;
            $product->save();
            return response()->json($product, 200);
        }
        else {
            //UPDATE-4
            return response()->json([
            'errors' => [
                'code' => 'ERROR-2',
                'title' => 'Not Found'
                ]], 404);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyProduct(String $id)
    {
        $producto = Product::find($id);
         if($producto){
            //DELETE-1
            Product::destroy($id);
            return response()->json(204);
        }
        else {
            //DELETE-2
            return response()->json([
            'errors' => [
                'code' => 'ERROR-2',
                'title' => 'Not Found'
                ]], 404);
        }
        
    }
}
