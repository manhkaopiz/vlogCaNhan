<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Image;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard()
    {
        $title = "Dashboard";

        return view('admin.dashboard', compact('title'));
//        return view('admin.test', compact('title'));
    }
    // Trang quản lý customer
    public function manageCustomers()
    {
        $customers = Customer::all();
        $title = 'Manage Customers';
        return view('admin.customer.index', compact('customers','title'));
    }
    public function searchcustomer(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $customers = Customer::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->get();
        } else {
            $customers = Customer::all();
        }
        $title = 'Manage Customers';
        return view('admin.customer.index', compact('customers', 'query','title'));
    }

    // Duyệt customer
    public function approveCustomer($id)
    {
        $customer = Customer::find($id);
        $customer->is_approved = 1;
        $customer->save();
        return redirect()->route('admin.customers')->with('message', 'Khach hàng đã được duyệt thành công');
    }

    // Chuyển trạng thái customer về chưa duyệt
    public function disapproveCustomer($id)
    {
        $customer = Customer::find($id);
        $customer->is_approved = 0;
        $customer->save();
        return redirect()->route('admin.customers')->with('message', 'Hủy thành công khách hàng');
    }
    public function deleteCustomer(Request $request, $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->delete();
        }
        return redirect()->route('admin.customers')->with('message', 'Khach hàng đã được xóa thành công');;
    }





    // Quản lý comment
    public function manageComments()
    {
        $comments = Comment::with('post', 'customer')->get();
        return view('admin.comments.index', compact('comments'));
    }

    // Xóa comment
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('admin.posts.title',$comment->post_id)
            ->with('success', 'Comment deleted successfully');
    }

    // Quản lý post
    public function adminindex(Request $request)
    {
        $title = "Admin";
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
            ->paginate(6);

        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'posts' => view('admin.posts.partials.posts', compact('posts','title'))->render()
            ]);
        }

        return view('admin.posts.index', compact('posts', 'categories','title'));

    }

    public function getPostsByCategory($id)
    {
        $posts = Post::with('images', 'category')
            ->where('category_id', $id)
            ->get();

        return response()->json([
            'posts' => view('admin.posts.partials.posts', compact('posts'))->render()
        ]);
    }

    public function admincreate()
    {
        $title = 'Add Post';
        $categories = Category::all();
        return view('admin.posts.create', compact('categories','title'));
    }

    public function adminstore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data['by_post'] = Auth::guard('admin')->id();

        $post = Post::create($data);

        if ($request->hasFile('image')) {
            // Delete old images
            foreach ($post->images as $image) {
                Storage::delete('public/' . $image->path);
                $image->delete();
            }

            $image = $request->file('image')->store('posts', 'public');
            $post->images()->create(['path' => $image]);
        }

        return redirect()->route('admin.posts')->with('success', 'Post created successfully');
    }

    public function admintitle($id)
    {
        $title = "Admin";
        $post = Post::with('category', 'images', 'comments.customer', 'ratings')->findOrFail($id);
        $comments = $post->comments;
        $categories = Category::all();
        $averageRating = $post->ratings->avg('rating');

        return view('admin.posts.title', compact('post', 'comments', 'averageRating','categories','title'));
    }

    public function adminshow($id)
    {
        $title = "Admin";
        $categories = Category::all();
        $post = Post::with('category', 'images', 'comments.customer', 'ratings')->findOrFail($id);
        return view('admin.posts.show', compact('post', 'categories','title'));
    }

    public function adminupdate(Request $request, Post $post)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data['by_post'] = Auth::guard('admin')->id();

        $post->update($data);

        if ($request->hasFile('image')) {
            // Delete old images
            foreach ($post->images as $image) {
                Storage::delete('public/' . $image->path);
                $image->delete();
            }

            // Store new image
            $image = $request->file('image')->store('posts', 'public');
            $post->images()->create(['path' => $image]);
        }

        return redirect()->route('admin.posts' )->with('success', 'Post updated successfully');
    }

    public function admindestroy( $id)
    {
        $category = Post::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.posts')->with('success', 'Post deleted successfully');
    }

    public function postsByCategory($id)
    {
        $title = "Posts";
        $category = Category::with('posts')->findOrFail($id);
        $posts = $category->posts;

        return view('admin.posts.index', compact('posts', 'category','title'));
    }


}
