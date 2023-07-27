<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request){
        $id = $request->id;
        $status = $request->status;
        $limit = $request->limit;

        if($id){
            $transaction = Transaction::with(['product_transactions.product'])->find($id);

            if($transaction){
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil ditemukan'
                );
            }
            else{
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }

        $transaction = Transaction::with(['product_transactions.product'])->where('users_id', Auth::user()->id);

        if($status){
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data transaksi berhasil ditemukan'
        );
    }

    public function checkout(Request $request){
        $request->validate([
            'product' => ['required', 'array'],
            'product.*.id' => ['exists:products,id'],
            'shipping_price' => ['required'],
            'total_price' => ['required'],
            'status' => ['required','in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED']
        ]);

        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'address' => $request->address,
            'shipping_price' => $request->shipping_price,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);

        foreach($request->product as $data){
            ProductTransaction::create([
                'users_id' => Auth::user()->id, 
                'products_id' => $data['id'], 
                'transactions_id' => $transaction->id, 
                'quantity' => $data['quantity']
            ]);
        }

        return ResponseFormatter::success(
            $transaction->load('product_transactions.product'), 
            'Transaksi Berhasil');
    }
}
