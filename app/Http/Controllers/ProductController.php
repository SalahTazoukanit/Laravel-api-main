<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            if($product->categories){
                $product->categorieTitle = $product->categories->pluck('name');
                unset($product->categories);
            }
        }

        return response()->json($products) ;
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

        if($request->has('categories') && !is_array($request->categories)){
            $categories = json_decode($request->categories, true);
            $request->merge(["categories"=>$categories]);
        }

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'string',
            'stock' => 'required',
            'price' => 'required',
            'categories'=> 'sometimes|array',
            'image'=> 'sometimes|image|max:5000',
        ]);

        $product =  Product::create($request->all());

        if(!empty($request->categories)) {
            $product->categories()->attach($categories);
        }

        if(!empty($request->image)) {
            $product->image = $request->image ;
            $this->storeImage($product);
        }
       
        return response()->json($product) ;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        return response()->json($product) ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $id)
    {
        $product = Product::find($id);
        return  response()->json($product) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'string',
            'stock' => 'required',
            'price' => 'required',
            'image'=> 'sometimes|image|max:5000',
          ]);
          $product = Product::find($id);
          $product->update($request->all());

          $this->storeImage($product);
          
          return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $product = Product::find($id);
        $product->categories()->detach();
        $product->delete();
        return "Le produit a été bien supprimé !" ;
    }

    private function storeImage(Product $product){

        if (request('image')) {
            $product->update([
                "image"=> request('image')->store('images','public'),
            ]);
        }

    }
}
