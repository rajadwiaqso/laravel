<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function autocomplete(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['products' => [], 'categories' => []]);
        }

        $products = Product::where('produk_name', 'like', "%$query%")
                            ->limit(5) // Limit the number of results
                            ->get(['produk_name', 'category']); // Only get the name

        $categories = Category::where('name', 'like', "%$query%")
                              ->limit(5) // Limit the number of results
                              ->get(['name', 'slug']); // Get name and slug


        return response()->json(['products' => $products, 'categories' => $categories]);
    }
}