@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">Posts</h2>
                <p class="lead text-muted">Upload images to send to a schedule run</p>
                @if (count($errors) > 0)
                    <div class="alert alert-danger col-md-8 offset-md-3">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Image</label>
                        <input class="form-control" type="file" name="image" placeholder="Select an image">
                    </div>
                    <div class="form-group">
                        <label>caption</label>
                        <textarea name="caption" class="form-control" cols="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
