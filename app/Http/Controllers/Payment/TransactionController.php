<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;

use App\Models\Shop;
use App\Models\User;
use App\Mail\TrxMail;
use App\Mail\Invoiced;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\APIPaymentResource;
use App\Http\Controllers\Payment\PaymentController;
use App\Models\Teacher;

class TransactionController extends Controller
{
    public function show($reference)
    {
        $tripay = new PaymentController();
        $detail = $tripay->detailTrx($reference);
        return new APIPaymentResource($detail);
    }

    public function store(Request $request)
    {

        $product = Teacher::find($request->product_id);
        $method = $request->method;

        // $stock = $product->stock;
        // $stock = $stock - 1;
        // $product->update([
        //     'stock' => $stock
        // ]);

        $req = new PaymentController();
        $transaction = $req->ReqPayment($method, $product);

        Transaction::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'reference' => $transaction->reference,
            'merchant_ref' => $transaction->merchant_ref,
            'total_amount' => $transaction->amount,
            'status' => $transaction->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'sucessfully created transaction',
            'data' => $transaction
        ], 200);
    }

    public function history()
    {
        $trx = Transaction::latest()->get();
        return new APIPaymentResource($trx);
    }
}
