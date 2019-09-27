<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

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
        // Create a new product
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
        return response()->json($producto,200);
    }
	
		/**
     * Display all resources.
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
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
        $product = Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyProduct(String $id)
    {
        $delete = Product::destroy($id);
        return response()->json(200);
    }
}
