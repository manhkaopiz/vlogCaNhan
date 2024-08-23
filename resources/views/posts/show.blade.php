<style>
    /* Container cho bài viết */
    .post-container {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    /* Tiêu đề bài viết */
    .post-name {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    /* Tiêu đề phụ */
    .post-title {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    /* Thông tin tác giả và ngày đăng */
    .post-info {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .post-admin-name,
    .post-created-at {
        margin: 0 10px;
        font-size: 1rem;
        text-align: right;
    }

    /* Các phần mô tả và danh mục */
    .post-description {
        margin-bottom: 20px;
        font-size: 1rem;
    }

    /* Thẻ h2 cho tiêu đề hình ảnh */
    h2 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    /* Hình ảnh */
    .img-thumbnail {
        max-width: 500px;
        margin-bottom: 20px;
    }


    .entered.loaded {
        max-width: 100%; /* Đảm bảo hình ảnh không vượt quá kích thước của phần chứa */
        height: auto;    /* Giữ tỷ lệ hình ảnh không bị biến dạng */
        display: block;  /* Loại bỏ khoảng trắng bên dưới hình ảnh */
        border-radius: 4px; /* Thêm bo góc cho hình ảnh */
        margin: 0 auto 20px; /* Căn giữa và khoảng cách dưới mỗi hình ảnh */
    }



</style>
@extends('layouts.app')

@section('content')
    <div class="post-container">
        <h1 class="post-name">{{ $post->name }}</h1>

{{--        <p class="post-title">{{ $post->title }}</p>--}}
        <p class="post-admin-name">Tác giả :{{ $post->admin->name }}</p>
        <p class="post-created-at"> Ngày đăng :{{ $post->created_at }}</p>

      
        @foreach ($post->images as $image)
            <img src="{{ asset('storage/' . $image->path) }}" alt="Image" class="img-thumbnail" style="max-width: 100%;">
        @endforeach

        <p class="post-category">Category: {{ $post->category->name }}</p>
        <p class="post-description">{!! $post->description !!}</p>

        <h2>Comments</h2>
        <ul id="comment-list" class="list-group">
            @foreach ($comments as $comment)
                <li id="comment-{{ $comment->id }}" class="list-group-item d-flex align-items-start">
                    <img src="{{ asset('storage/' . ($comment->customer->profile->avatar ?: 'avatars/default-avatar.png')) }}" alt="Avatar" class="avatar-img mr-3">
                    <div>
                        <span class="comment-content">{{ $comment->customer->profile->name ?: $comment->customer->name }}: {{ $comment->content }}</span>
                        @if (Auth::check() && Auth::guard('customer')->user()->id == $comment->customer_id)
                            <a href="#" class="edit-comment ml-2" data-id="{{ $comment->id }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="edit-comment-form mt-2" id="edit-comment-form-{{ $comment->id }}" style="display: none;">
                                @csrf
                                @method('PUT')
                                <textarea name="content" rows="2" class="form-control">{{ $comment->content }}</textarea>
                                <button type="submit" class="btn btn-primary btn-submit mt-2">Update Comment</button>
                                <button type="button" class="btn btn-secondary btn-submit cancel-edit mt-2" data-id="{{ $comment->id }}">Cancel</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        <!-- Điều hướng phân trang cho bình luận -->
        <div class="pagination mt-3">
            @if ($comments->onFirstPage())
                <span class="disabled btn btn-secondary">Previous</span>
            @else
                <a href="{{ $comments->previousPageUrl() }}" class="btn btn-primary">Previous</a>
            @endif

            @foreach ($comments->links()->elements[0] as $page => $url)
                @if ($page == $comments->currentPage())
                    <span class="current-page btn btn-info">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="btn btn-light">{{ $page }}</a>
                @endif
            @endforeach

            @if ($comments->hasMorePages())
                <a href="{{ $comments->nextPageUrl() }}" class="btn btn-primary">Next</a>
            @else
                <span class="disabled btn btn-secondary">Next</span>
            @endif
        </div>

        @if (Auth::check())
            <form action="{{ route('comments.store', ['id' => $post->id]) }}" method="POST" class="mt-3">
                @csrf
                <div class="form-group">
                    <label for="comment">Add a Comment</label>
                    <textarea name="content" id="comment" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-submit">Submit Comment</button>
            </form>
        @endif

        <h2>Ratings</h2>
        <p>Average Rating: {{ $averageRating }}</p>

        @if (Auth::check())
            <form action="{{ route('ratings.store', ['id' => $post->id]) }}" method="POST" class="rating-select">
                @csrf
                <div class="form-group">
                    <label for="rating">Rate this post:</label>
                    <div class="star-rating">
                        <input type="hidden" name="rating" id="rating" required>
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-submit">Submit Rating</button>
            </form>
        @endif

        <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-3">Back to Posts</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.edit-comment').click(function(e) {
                e.preventDefault();
                var commentId = $(this).data('id');
                $('#edit-comment-form-' + commentId).toggle();
            });

            $('.cancel-edit').click(function() {
                var commentId = $(this).data('id');
                $('#edit-comment-form-' + commentId).hide();
            });

            $('.edit-comment-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var commentId = form.attr('id').split('-').pop();
                var formData = form.serialize();
                var url = "{{ route('comments.update', ':id') }}".replace(':id', commentId);

                $.ajax({
                    url: url,
                    method: 'POST', // Use POST method to handle the PUT request
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#comment-' + commentId + ' .comment-content').text(response.comment.content);
                            form.hide();
                        } else {
                            alert('An error occurred while updating the comment.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while updating the comment.');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;

                    // Reset all stars
                    stars.forEach(s => s.classList.remove('selected'));

                    // Highlight the selected stars
                    this.classList.add('selected');
                    let prevSibling = this.previousElementSibling;
                    while (prevSibling) {
                        prevSibling.classList.add('selected');
                        prevSibling = prevSibling.previousElementSibling;
                    }
                });

                star.addEventListener('mouseover', function() {
                    // Reset all stars
                    stars.forEach(s => s.classList.remove('hovered'));

                    // Highlight the hovered stars
                    this.classList.add('hovered');
                    let prevSibling = this.previousElementSibling;
                    while (prevSibling) {
                        prevSibling.classList.add('hovered');
                        prevSibling = prevSibling.previousElementSibling;
                    }
                });

                star.addEventListener('mouseout', function() {
                    // Remove hover class after mouse out
                    stars.forEach(s => s.classList.remove('hovered'));
                });
            });
        });
    </script>
@endsection
