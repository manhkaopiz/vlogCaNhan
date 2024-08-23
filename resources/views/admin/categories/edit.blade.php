@extends('admin.dashboard')

@section('content')
    <h1>Edit Category</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="parent_id">Parent Category:</label>
            <select id="parent_id" name="parent_id" class="form-control">
                <option value="0">None</option>
                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" class="form-control">{{ old('content', $category->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="active">Active:</label>
            <input type="checkbox" id="active" name="active" value="1" {{ $category->active ? 'checked' : '' }}>
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
    </form>
@endsection
