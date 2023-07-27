<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request){
        $id = $request->id;
        $categories_id = $request->categories_id;
        $name = $request->name;
        $price_min = $request->price_min;
        $price_max = $request->price_max;
        $description = $request->description;
        $tags = $request->tags;
        $limit = $request->limit;

        if($id){
            $product = Product::with(['category', 'galleries'])->find($id);

            if($product){
                return ResponseFormatter::success(
                    $product,
                    'Data produk berhasil ditemukan'
                );
            }
            else{
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404
                );
            }
        }

        $product = Product::with(['category', 'galleries']);

        if($name){
            $product->where('name', 'like', '%'.$name.'%');
        }

        if($description){
            $product->where('description', 'like', '%'.$description.'%');
        }

        if($tags){
            $product->where('tags', 'like', '%'.$tags.'%');
        }

        if($price_min){
            $product->where('price', '>=', $price_min);
        }

        if($price_max){
            $product->where('price', '<=', $price_max);
        }

        if($categories_id){
            $product->where('categories_id', '=', $categories_id);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data produk berhasil ditemukan'
        );
    }
}
