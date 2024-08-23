@extends('admin.dashboard')

@section('head')
    <script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <h1>Posts</h1>

        <!-- Thanh tìm kiếm -->
        <form action="{{ route('admin.posts') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search posts..." value="{{ request()->input('query') }}">
                <input type="hidden" name="category_id" id="category_id" value="{{ request()->input('category_id') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>

        @isset($categories)
            <button id="toggleCategories" class="btn btn-info mb-3">Show Categories</button>
            <div class="categories" id="categoriesList">
                <h2>Categories</h2>
                <ul class="list-group">
                    @foreach($categories as $category)
                        <li class="list-group-item">
                            <a href="#" class="category-link" data-category-id="{{ $category->id }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endisset

        <div class="row" id="postsList">
            @foreach ($posts as $post)
                <div class="col-md-4">
                    <div class="card2 mb-4">
                        @if($post->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $post->images->first()->path) }}" alt="Image" class="card-img">
                        @endif
                        <div class="card-body">
                            <h4 class="card-name">{{ $post->name }}</h4>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">Category: {{ $post->category->name }}</p>

                            <a href="{{ route('admin.posts.title', $post->id) }}" class="btn btn-primary">View Details</a>
                            <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Hiển thị liên kết phân trang -->
        <div class="pagination">
            {{ $posts->links() }}
        </div>
    </div>
@endsection

@section('footer')
    <script>
        document.getElementById('toggleCategories').addEventListener('click', function() {
            var categoriesList = document.getElementById('categoriesList');
            categoriesList.classList.toggle('active');
            this.textContent = categoriesList.classList.contains('active') ? 'Hide Categories' : 'Show Categories';
        });

        document.querySelectorAll('.category-link').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var categoryId = this.getAttribute('data-category-id');
                document.getElementById('category_id').value = categoryId;

                $.ajax({
                    url: "{{ route('admin.posts') }}",
                    method: "GET",
                    data: {
                        category_id: categoryId,
                        query: document.querySelector('input[name="query"]').value
                    },
                    success: function(data) {
                        $('#postsList').html(data.posts);
                        $('.pagination').html(data.pagination);
                    },
                    error: function() {
                        alert('Failed to load posts.');
                    }
                });
            });
        });
    </script>
@endsection
