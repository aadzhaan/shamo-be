<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function all(Request $request){
        $id = $request->id;
        $name = $request->name;
        $product = $request->product;
        $limit = $request->limit;

        if($id){
            $category = Category::with(['products'])->find($id);

            if($category){
                return ResponseFormatter::success(
                    $category,
                    'Data kategori berhasil ditemukan'
                );
            }
            else{
                return ResponseFormatter::error(
                    null,
                    'Data kategori tidak ada',
                    404
                );
            }
        }

        $category = Category::query();

        if($name){
            $category->where('name', 'like', '%'.$name.'%');
        }

        if($product){
            $category->with(['products']);
        }

        return ResponseFormatter::success(
            $category->paginate($limit),
            'Data kategori berhasil ditemukan'
        );
    }
}
