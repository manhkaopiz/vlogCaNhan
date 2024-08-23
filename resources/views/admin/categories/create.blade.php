
@extends('admin.dashboard')

@section('content')

<h1>Create Category</h1>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">New</h3>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="parent_id">Parent ID:</label>
                <select class="form-control" name="parent_id">
                    <option value="0">Danh mục cha</option>
                    @foreach($categories as $cate)
                        <option value="{{ $cate->id }}">{{  $cate->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" class="form-control" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" id="content" name="content"></textarea>
            </div>

            <div class="form-check">
                <label for="active">Active:</label>
                <input type="checkbox" id="active" name="active" value="1" checked>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

@endsection

