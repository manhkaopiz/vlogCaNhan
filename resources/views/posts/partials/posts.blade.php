<div class="row">
    @foreach ($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card mb-4">
                @if($post->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $post->images->first()->path) }}" alt="Image" class="card">
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
