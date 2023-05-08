<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->has('image')) {
            $image_path = $request->file('image')->store('images', 'public');
            return Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $image_path,
            ]);
        }
        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->update($request->except('image'));

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $image_path = $request->file('image')->store('images', 'public');
            $product->image = $image_path;
            $product->save();
        }

        return $product;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Product::destroy($id);
    }

    /**
     * search for specified resource from storage.
     */
    public function search(string $name)
    {
        return Product::where('name', 'like', '%' . $name . '%')->get();
    }
}
