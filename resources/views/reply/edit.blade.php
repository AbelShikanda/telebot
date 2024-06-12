@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px">
            <div class="col-6">
                <form action="{{ route('replies.update', $replies->id) }}" method="POST">
                    @csrf
                    @method('Patch')
                    <div class="form-group">
                        <label>KeyWords</label>
                        <input type="text" class="form-control" name="keywords" value="{{ $replies->keyword }}">
                        <small id="emailHelp" class="form-text text-muted">add any keywords you can think of</small>
                    </div>
                    <div class="form-group">
                        <label>Reply</label>
                        <textarea name="reply" class="form-control" cols="30" rows="10" placeholder="{{ $replies->response }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
