<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Product ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Product name"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Product price"
 *     ),
 *     @OA\Property(
 *         property="stock",
 *         type="integer",
 *         description="Product stock"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Product description"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         description="URL of the product image"
 *     ),
 *     @OA\Property(
 *         property="categories",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Categorie")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time when the product was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time when the product was last updated"
 *     )
 * )
 */

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'image',
    ];

    public function categories(){

        return $this->belongsToMany(Categorie::class);

    }
}
