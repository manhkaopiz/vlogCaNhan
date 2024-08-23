<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $title = "Categories";
        $categories = Category::all();
        return view('admin.categories.index', compact('categories','title'));
    }
    public function showPosts(Category $category)
    {
        $posts = $category->posts()->with('images')->get();
        $categories = Category::all();

        return view('posts.index', compact('posts', 'categories'));
    }
    public function adminShowPosts(Category $category)
    {
        $posts = $category->posts()->with('images')->get();
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $title = "Add Category";
        return view('admin.categories.create',[
            'categories' => Category::all(),
            'title' => $title

        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        Category::create($data);
        $title = "Categories";

        return redirect()->route('admin.categories',compact('title'))->with('success','Category created successfully');
    }

    public function edit($id)
    {
        $title = "Edit Category";
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->whereDoesntHave('children', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();

        return view('admin.categories.edit', compact('category', 'categories','title'));
    }


    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        $data['parent_id'] = $data['parent_id'] ?? 0;

        if ($request->parent_id == $id || $this->isInvalidParent($request->parent_id, $id)) {
            return redirect()->back()->withErrors(['parent_id' => 'Invalid parent category.']);
        }

        $data['active'] = isset($data['active']) ? ($data['active'] ? 1 : 0) : 0;

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully');
    }

    protected function isInvalidParent($parentId, $currentId = null)
    {

        if ($parentId == $currentId) {
            return true;
        }
        $parent = Category::find($parentId);
        while ($parent) {
            if ($parent->id == $currentId) {
                return true;
            }
            $parent = $parent->parent;
        }
        return false;
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);


        $this->deleteChildren($category);

        $category->delete();

        return redirect()->route('admin.categories');
    }

    protected function deleteChildren($category)
    {
        $children = Category::where('parent_id', $category->id)->get();
        foreach ($children as $child) {
            $this->deleteChildren($child);
            $child->delete();
        }
    }


}
