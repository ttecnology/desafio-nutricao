<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'status',
        'imported_t',
        'url',
        'creator',
        'created_at',
        'last_modified_t',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'serving_quantity',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url',
    ];

    public function toSearchableArray()
    {
        return $this->only(['id', 'product_name', 'brands', 'categories', /* adicione mais campos conforme necessário */]);
    }

    protected function importData($data) {
        $counter = 0;

        foreach ($data as $productData) {
            if ($counter >= 100) {
                break;
            }

            $product = new Product($productData);
            $product->imported_t = now();
            $product->created_at = now();
            $product->last_modified_t = now();

            $product->save();

            $counter++;
        }
    }
}
