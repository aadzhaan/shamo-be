<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'users_id', 'address', 'shipping_price', 'total_price', 'status', 'payment'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function product_transactions(){
        return $this->hasMany(ProductTransaction::class, 'transactions_id', 'id');
    }
}
