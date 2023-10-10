<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function apiDetails()
    {
        try {
            DB::connection()->getPdo();
            $databaseConnection = true;
        } catch (\Exception $e) {
            $databaseConnection = false;
        }

        return response()->json([
            'status' => 'OK',
            'database_connection' => $databaseConnection,
            'last_cron_execution' => '2023-10-10 12:00:00',
            'uptime' => exec('uptime'),
            'memory_usage' => memory_get_usage(),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Product::search($query)->get();

        return response()->json($results);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'trash']);

        return response()->json(['message' => 'Product status changed to trash']);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function index()
    {
        $products = Product::paginate(10);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products|max:255',
            'product_name' => 'required|max:255',
            'quantity' => 'required|integer|min:1',
            'brands' => 'nullable|max:255',
            'categories' => 'nullable|max:255',
            'labels' => 'nullable|max:255',
            'cities' => 'nullable|max:255',
            'purchase_places' => 'nullable|max:255',
            'stores' => 'nullable|max:255',
            'ingredients_text' => 'nullable',
            'traces' => 'nullable|max:255',
            'serving_size' => 'nullable|max:255',
            'serving_quantity' => 'nullable|numeric|min:0',
            'nutriscore_score' => 'nullable|integer|min:0|max:100',
            'nutriscore_grade' => 'nullable|max:1',
            'main_category' => 'nullable|max:255',
            'image_url' => 'nullable|url',
        ]);

        $productData = $request->all();
        $productData['imported_t'] = now();
        $productData['created_at'] = now();
        $productData['last_modified_t'] = now();
        
        $product = Product::create($productData);
        return response()->json(['message' => 'Product created successfully']);
    }

}