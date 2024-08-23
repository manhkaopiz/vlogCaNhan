<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');

        $posts = Post::with('category', 'images')
            ->when($query, function ($q) use ($query) {
                return $q->where('title', 'LIKE', "%$query%")
                    ->orWhere('name', 'LIKE', "%$query%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->get();

        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([

                'posts' => view('posts.partials.posts', compact('posts'))->render()
            ]);
        }

        $customer = Auth::guard('customer')->user();
        $profile = $customer ? $customer->profile : null;


        return view('posts.index', compact('posts', 'categories','customer','profile'));
    }


//    public function create()
//    {
//        $categories = Category::all();
//        return view('admin.posts.create', compact('categories'));
//    }

//    public function store(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'title' => 'required|string|max:255',
//            'description' => 'required',
//            'category_id' => 'required|exists:categories,id',
//            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//        ]);
//
//        $post = Post::create([
//            'name' => $request->name,
//            'title' => $request->title,
//            'description' => $request->description,
//            'category_id' => $request->category_id,
//            'status' => $request->status ? 1 : 0,
//        ]);
//
//        if ($request->hasFile('images')) {
//            foreach ($request->file('images') as $image) {
//                $path = $image->store('images', 'public');
//                Image::create([
//                    'path' => $path,
//                    'post_id' => $post->id,
//                ]);
//            }
//        }
//
//        return redirect()->route('posts.index');
//    }
//

    public function show($id)
    {
        $post = Post::with('category', 'images', 'comments.customer', 'ratings')->findOrFail($id);

        // Sử dụng paginate trên đối tượng Query Builder thay vì Collection
        $comments = $post->comments()->paginate(5);

        $averageRating = $post->ratings->avg('rating');

        $customer = Auth::guard('customer')->user();
        $profile = $customer ? $customer->profile : null;

        return view('posts.show', compact('post', 'customer', 'profile', 'comments', 'averageRating'));
    }



//    public function edit(Post $post)
//    {
//        $categories = Category::all();
//        return view('admin.posts.edit', compact('post', 'categories'));
//    }
//
//    public function update(Request $request, Post $post)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'title' => 'required|string|max:255',
//            'description' => 'required',
//            'category_id' => 'required|exists:categories,id',
//            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//        ]);
//
//        $post->update([
//            'name' => $request->name,
//            'title' => $request->title,
//            'description' => $request->description,
//            'category_id' => $request->category_id,
//            'status' => $request->status ? 1 : 0,
//        ]);
//
//        if ($request->hasFile('images')) {
//            foreach ($request->file('images') as $image) {
//                $path = $image->store('images', 'public');
//                Image::create([
//                    'path' => $path,
//                    'post_id' => $post->id,
//                ]);
//            }
//        }
//
//        return redirect()->route('posts.index');
//    }

//    public function destroy(Post $post)
//    {
//        // Xóa tất cả các hình ảnh liên quan trước khi xóa bài viết
//        $post->images()->each(function ($image) {
//            \Storage::disk('public')->delete($image->path);
//            $image->delete();
//        });
//
//        $post->delete();
//
//        return redirect()->route('posts.index');
//    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with('category', 'images')
            ->get();

        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with('posts', 'children')
            ->get();

        return view('posts.index', compact('posts', 'categories', 'query'));
    }
}
