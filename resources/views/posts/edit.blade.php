@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px">
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
            <div class="col-6">
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('Patch')
                    <div class="form-group">
                        <label>Image</label>
                        <input class="form-control" type="file" name="image" placeholder="Select an image" value="{{ $post->image }}">
                        <small id="emailHelp" class="form-text text-muted">add any keywords you can think of</small>
                    </div>
                    <div class="form-group">
                        <label>caption</label>
                        <textarea name="caption" class="form-control" cols="30" rows="10" placeholder="{{ $post->caption }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
