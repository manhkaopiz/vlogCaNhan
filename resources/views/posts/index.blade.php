

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Posts</h1>
    <form id="searchForm" action="{{ route('posts.index') }}" method="GET" class="mb-4">
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

    <div id="postsList" class="row">
        @foreach ($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card2 mb-4">
                    @if($post->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $post->images->first()->path) }}" alt="Image" class="card-img">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->name }}</h5>
                        <p class="card-text">{{ $post->title }}</p>
                        <p class="card-text">Category: {{ $post->category->name }}</p>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="/template/admin/plugins/jquery/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
            document.getElementById('searchForm').submit();
        });
    });
</script>
@endsection
