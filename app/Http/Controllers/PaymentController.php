<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

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
    




}
