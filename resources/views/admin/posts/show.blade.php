@extends('admin.dashboard')

@section('head')
    <script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')
<div class="container mt-5">
    <h1>Edit Post</h1>
    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $post->name) }}" required>
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        @if($post->images->isNotEmpty())
            <div class="form-group">
                <label>Current Image</label>
                @foreach($post->images as $image)
                    <img src="{{ asset('storage/' . $image->path) }}" alt="Image" style="max-width: 200px;">
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ old('description', $post->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

@endsection

@section('footer')
    <script>
        CKEDITOR.replace('description');
        document.getElementById('toggleCategories').addEventListener('click', function() {
            var categoriesList = document.getElementById('categoriesList');
            categoriesList.classList.toggle('active');
            this.textContent = categoriesList.classList.contains('active') ? 'Hide Categories' : 'Show Categories';
        });
    </script>
@endsection
