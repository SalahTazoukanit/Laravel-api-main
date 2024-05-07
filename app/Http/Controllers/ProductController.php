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

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Liste des produits",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Retourne la liste des produits",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     )
     * )
     */ 
    public function index()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            if($product->categories){
                $product->categorie = $product->categories->pluck('name');
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

     /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     summary="Ajouter un nouveau produit",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retourne le produit ajouté",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
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

     /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     summary="Afficher un produit spécifique",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retourne le produit spécifique",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function show(string $id)
    {
        $product = Product::find($id);

            if($product->categories){
                $product->categorieTitle = $product->categories->pluck('name');
                unset($product->categories);
            }

        return response()->json($product) ;
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     
    * @OA\Get(
    *     path="/api/v1/products/{id}/edit",
    *     summary="Afficher le formulaire d'édition d'un produit",
    *     tags={"Products"},
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID du produit à éditer",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Retourne le produit à éditer",
    *         @OA\JsonContent(ref="#/components/schemas/Product")
    *     )
    * )
    */

    public function edit(Product $id)
    {
        $product = Product::find($id);
        return  response()->json($product) ;
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     summary="Mettre à jour un produit",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retourne le produit mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */

    public function update(Request $request,string $id)
    {
        //pour mettre à jour les categorie du produit
        if($request->has('categories') && !is_array($request->categories)){
            $categories = json_decode($request->categories, true);
            $request->merge(["categories"=>$categories]);
        }

        $request->validate([
            
            'name' => 'required|max:255',
            'description' => 'string',
            'stock' => 'required',
            'price' => 'required',
            'image'=> 'sometimes|image|max:5000',
            'categories'=>'sometimes|array',

        ]);
        
          $product = Product::find($id);
          $product->update($request->all());

            if(!empty($request->categories)) {
                    $product->categories()->attach($categories);
            }

          $this->storeImage($product);
          
          return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     summary="Supprimer un produit",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du produit à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message de confirmation de suppression"
     *     )
     * )
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
