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
