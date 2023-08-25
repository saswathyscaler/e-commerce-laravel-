<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{

    //create ratings
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|between:1,5',
        ]);

        $user = Auth::user();
        $product_id = $request->input('product_id');
        $rating_value = $request->input('rating');

        $existingRating = Rating::where('user_id', $user->id)
            ->where('product_id', $product_id)
            ->first();

        if ($existingRating) {
            $existingRating->rating = $rating_value;
            $existingRating->save();
        } else {
            Rating::create([
                'user_id' => $user->id,
                'product_id' => $product_id,
                'rating' => $rating_value,
            ]);
        }

        return response()->json([
            'message' => 'Rating stored successfully',
            'status' => 200,
        ]);
    }


    //Get Alll Ratings

    public function getAllRatingsForProduct($product_id)
    {
        $ratings = Rating::where('product_id', $product_id)->get();

        return response()->json([
            'ratings' => $ratings,
            'message' => 'success',
            'status' => 200,
        ]);
    }

    //Edit rating

    public function editRating(Request $request, $id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found',
                'status' => 404,
            ], 404);
        }

        $user = Auth::user();
        if ($rating->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to edit this rating',
                'status' => 403,
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $rating_value = $request->input('rating');
        $rating->rating = $rating_value;
        $rating->save();

        return response()->json([
            'message' => 'Rating edited successfully',
            'status' => 200,
        ]);
    }



    //Delete Rating 
    public function deleteRating($id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found',
                'status' => 404,
            ], 404);
        }

        $user = Auth::user();
        if ($rating->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this rating',
                'status' => 403,
            ], 403);
        }

        $rating->delete();

        return response()->json([
            'message' => 'Rating deleted successfully',
            'status' => 200,
        ]);
    }
}
