<?php

namespace App\Http\Controllers;

use App\Models\FormData;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\Cart;

class PaymentController extends Controller
{
    public function makePayment(Request $request)
    {
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        $order = $api->order->create([

            'amount' => $request->input('amount') * 100,
            'currency' => 'INR',
            'receipt' => 'order_receipt',
        ]);;

        return response()->json(['order_id' => $order->id]);
    }



    public function handlePayment(Request $request)
{
    try {
        $user = auth()->user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        $totalAmount = 0;
        $orderDetails = [];

        foreach ($cartItems as $cartItem) {
            $totalAmount += ($cartItem->quantity * $cartItem->product->price);

            $orderDetails[] = [
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
            ];
        }

        $formData = new FormData([
            'name' => $request->input('name'),
            'mobileNumber' => $request->input('mobileNumber'),
            'alternateNumber' => $request->input('alternateNumber'),
            'address' => $request->input('address'),
            'user_id' => $user->id,
            'orderDetails' => $orderDetails,
            'total_amount' => $totalAmount,
        ]);

        // Assuming you have a relationship between FormData and User, you can associate the user
        $formData->user()->associate($user);
        $formData->save();

            $formData->save();
            $user = auth()->user(); 
            Mail::to($user)->send(new OrderConfirmation($formData));
            return response()->json(['message' => 'Form data saved successfully'], 200);
        } catch (\Exception $e) {

            error_log("Error saving form data: " . $e->getMessage());

            return response()->json(['message' => 'Error saving form data'], 500);
        }
    }

    
public function getAllFormData()
{
    try {
        $formData = FormData::all(); 
        return response()->json(['data' => $formData], 200);
    } catch (\Exception $e) {
        error_log("Error retrieving form data: " . $e->getMessage());
        return response()->json(['message' => 'Error retrieving form data'], 500);
    }
}

    public function placeOrder(Request $request)
    {
        try {
            $formData = new FormData([
                'name' => $request->input('name'),
                'mobileNumber' => $request->input('mobileNumber'),
                'alternateNumber' => $request->input('alternateNumber'),
                'address' => $request->input('address'),
                'user_id' => auth()->id(), 
            ]);

            $formData->save();

            return response()->json(['message' => 'Payment and order placed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while placing the order'], 500);
        }
    }
}
