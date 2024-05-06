<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     title="Categorie",
 *     description="Categorie model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Categorie ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Categorie name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Categorie description"
 *     ),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time when the categorie was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time when the categorie was last updated"
 *     )
 * )
 */
class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

   
    public function products(){

        return $this->belongsToMany(Product::class);

    }

}
