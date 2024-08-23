<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'content' => $request->input('content'),
            'post_id' => $post,
            'customer_id' => Auth::id(),
        ]);

        return redirect()->route('posts.show', $post);
    }
    public function destroy($id)
    {

        $comment = Comment::findOrFail($id);

        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }


    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        // Check if comment exists and if the user is authorized
        if ($comment && Auth::check() && Auth::user()->id == $comment->customer_id) {
            // Validate the request
            $request->validate([
                'content' => 'required|string|max:255',
            ]);

            // Update the comment
            $comment->content = $request->input('content');

            // Save the comment
            if ($comment->save()) {
                // Return a JSON response
                return response()->json(['success' => true, 'comment' => $comment]);
            }
        }

        // Return a JSON response with an error
        return response()->json(['success' => false, 'message' => 'An error occurred while updating the comment.']);
    }








}
