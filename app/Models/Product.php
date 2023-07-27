<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'price', 'description', 'tags', 'categories_id'
    ];

    public function galleries(){
        return $this->hasMany(Gallery::class, 'products_id', 'id');
    }

    public function categories(){
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }

    public function product_transactions(){
        return $this->hasMany(ProductTransaction::class, 'products_id', 'id');
    }
}
