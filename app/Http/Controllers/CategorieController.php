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
     * Store a newly created resource in storage.
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
    public function show(string $id)
    {
        // dd($product);
        $categorie = Categorie::find( $id);
        return response()->json($categorie) ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $id)
    {
        $categorie = Categorie::find($id);
        return  response()->json($categorie) ;
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
          ]);
          $categorie = Categorie::find($id);
          $categorie->update($request->all());
          return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $categorie = Categorie::find($id);
        $categorie->products()->detach();
        $categorie->delete();
        return "Le produit a été bien supprimé !" ;
    }
}
