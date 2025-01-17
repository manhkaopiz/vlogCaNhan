<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, $post)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        Rating::updateOrCreate(
            [
                'post_id' => $post,
                'customer_id' => Auth::id(),
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return redirect()->route('posts.show', $post)->with('success', 'Rating submitted successfully.');
    }


}
