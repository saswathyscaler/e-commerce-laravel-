<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function makePayment(Request $request)
    {
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    
        $order = $api->order->create([
            'amount' => $request->input('amount') * 100, 
            'currency' => 'INR',
            'receipt' => 'order_receipt',
        ]);
    
        return response()->json(['order_id' => $order->id]);
    }


    public function store(Request $request)
    {
        $user = Auth::user(); 
        $total = $request->input('amount');
        $paymentId = $request->input('payment_id');

        $order = Order::create([
            'userid' => $user->id,
            'payment_id' => $paymentId,
            'amount' => $total,
            'address' => $request->input('address'),
        ]);

        return response()->json(['message' => 'Order placed successfully']);
    }



}
