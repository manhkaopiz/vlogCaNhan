@extends('admin.dashboard')

@section('head')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="/ckeditor/ckeditor.js"></script>

@endsection

@section('content')
    <div class="container mt-5">
         <h1 style="" class="post-name"><strong>{{ $post->name }}</strong></h1>




        <div class="images">
            @foreach ($post->images as $image)
                <img src="{{ asset('storage/' . $image->path) }}" alt="Image">
            @endforeach
        </div>

        <p class="post-description">{{ $post->title }}</p>
        <p class="post-description">{{ $post->category->name }}</p>
        <p class="post-description">{!! $post->description !!}</p>
        <h2>Comments</h2>
        <ul class="comments-list">
            @foreach ($comments as $comment)
                <li>
                    <strong>{{ $comment->customer->name }}:</strong> {{ $comment->content }}
                    <form action="{{ route('admin.comments.destroy', $comment->id ) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete Comment</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <h2>Ratings</h2>
        <p class="ratings-summary">Average Rating: {{ $averageRating }}</p>

        <a href="{{ route('admin.posts') }}" class="btn btn-secondary back-link">Back to Posts</a>
    </div>
@endsection

@section('footer')
    <script>
        // If you have a toggle button for categories
        document.getElementById('toggleCategories')?.addEventListener('click', function() {
            var categoriesList = document.getElementById('categoriesList');
            if (categoriesList) {
                categoriesList.classList.toggle('active');
                this.textContent = categoriesList.classList.contains('active') ? 'Hide Categories' : 'Show Categories';
            }
        });
    </script>
@endsection
