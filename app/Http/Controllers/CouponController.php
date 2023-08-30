<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;


class CouponController extends Controller
{
    // ADD COUPON
    public function createCoupon(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'code' => 'required|unique:coupons',
            'min_order' => 'required|numeric',
            'value' => 'required|numeric',
            'type' => 'required|in:percentage,fixed',
            'is_active' => 'boolean',
        ]);

        $coupon = Coupon::create($validatedData);

        return response()->json([
            'message' => 'Coupon created successfully',
            'coupon' => $coupon,
        ]);
    }

    // GET COUPON

    public function getAllCoupons()
    {
        $coupons = Coupon::all();                       
    
        return response()->json([
            'coupons' => $coupons,
        ]);
    }


// single coupon
    public function showCoupon($id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'coupon not found'], 404);
        }

        return response()->json(['coupon' => $coupon], 200);
    }
    




//EDIT COUPON

public function editCoupon(Request $request, $id)
{
    $coupon = Coupon::findOrFail($id);

    $validatedData = $request->validate([
        'title' => 'required',
        'code' => 'required|unique:coupons,code,' . $coupon->id,
        'min_order' => 'required|numeric',
        'value' => 'required|numeric',
        'type' => 'required|in:percentage,fixed',
        'is_active' => 'boolean',
    ]);

    $coupon->update($validatedData);

    return response()->json([
        'message' => 'Coupon updated successfully',
        'coupon' => $coupon,
    ]);
}


//DELETE COUPON

public function deleteCoupon($id)
{
    $coupon = Coupon::findOrFail($id);
    $coupon->delete();

    return response()->json([
        'message' => 'Coupon deleted successfully',
    ]);
}






}
