@extends('admin.dashboard')

@section('content')
    <h1>Manage Categories</h1>
    <div class="category-container">

        <div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-green">Add New Category</a>
        </div>
        <div class="search-container">
            <form action="{{ route('customer.search') }}" method="GET">
                <input type="text" placeholder="Search.." name="query" class="search-input" value="{{ $query ?? '' }}">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
    </div>

    <table id="category" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Parent Name</th>
            <th>Description</th>
            <th>Content</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent ? $category->parent->name : 'Cha' }}</td>
                <td>{{ $category->description }}</td>
                <td>{{ $category->content }}</td>
                <td>
                    @if(!$category->active)
                        <button type="submit" class="button button-disapprove" onclick="confirmAction(event, 'Bạn có muốn ẩn Danh mục?')">DisActive</button>
                    @else
                        <button type="submit" class="button button-approve" onclick="confirmAction(event, 'Bạn có muốn hiện danh mục?')">Active</button>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category->id) }}">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="confirmAction(event, 'Are you sure you want to delete this category?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button-delete">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
