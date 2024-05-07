<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Liste des catégories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Retourne la liste des catégories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Categorie")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json($categories) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    

    /**
     * @OA\Schema(
     *     schema="CategorieRequest",
     *     title="Categorie Request",
     *     description="Request body for creating or updating a Categorie.",
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Name of the categorie",
     *         example="Category Name"
     *     ),
     *     @OA\Property(
     *         property="description",
     *         type="string",
     *         description="Description of the categorie",
     *         example="Category Description"
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'string',
            'stock' => 'required',
            'price' => 'required',
        ]);

        $categorie =  Categorie::create($request->all());
        return response()->json($categorie) ;
    }

    /**
     * Display the specified resource.
     */

     /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     summary="Afficher une catégorie spécifique",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retourne la catégorie spécifique",
     *         @OA\JsonContent(ref="#/components/schemas/Categorie")
     *     )
     * )
     */ 
    public function show(string $id)
    {
        // dd($product);
        $categorie = Categorie::find( $id);
        return response()->json($categorie) ;
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}/edit",
     *     summary="Afficher le formulaire d'édition d'une catégorie",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie à éditer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retourne la catégorie à éditer",
     *         @OA\JsonContent(ref="#/components/schemas/Categorie")
     *     )
     * )
     */ 
    public function edit(Categorie $id)
    {
        $categorie = Categorie::find($id);
        return  response()->json($categorie) ;
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     summary="Mettre à jour une catégorie",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategorieRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie mise à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Categorie")
     *     )
     * )
     */
    public function update(Request $request,string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'string',
            'stock' => 'required',
            'price' => 'required',
          ]);
          $categorie = Categorie::find($id);
          $categorie->update($request->all());
          return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     summary="Supprimer une catégorie",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie supprimée avec succès"
     *     )
     * )
     */ 
    public function destroy(String $id)
    {
        $categorie = Categorie::find($id);
        $categorie->products()->detach();
        $categorie->delete();
        return "Le produit a été bien supprimé !" ;
    }
}
