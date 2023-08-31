<?php

namespace App\Http\Controllers;

use App\Models\FormData;
use App\Models\Order;
use App\Models\User;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
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
            $formData = new FormData([
                'name' => $request->input('name'),
                'mobileNumber' => $request->input('mobileNumber'),
                'alternateNumber' => $request->input('alternateNumber'),
                'address' => $request->input('address'),
                'user_id' => auth()->id(), 
                'order_id' => $request->input('order_id'), 
            ]);

            $formData->save();
            $user = auth()->user(); // Assuming you're using the default Laravel authentication
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
        $formData = FormData::all(); // Retrieve all data from the FormData table
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
