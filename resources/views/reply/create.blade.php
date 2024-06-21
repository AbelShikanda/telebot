@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">Private chat responses</h2>
                <p class="text-muted">Responses for the private interaction with the bot.</p>
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
                <div class="card-deck">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Responses form</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('replies.store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">KeyWords</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="keywords" id="inputEmail3"
                                            placeholder="Enter Keyword">
                                        <small id="emailHelp" class="form-text text-muted">add any keywords you can think
                                            of</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3" for="exampleFormControlTextarea1">Group Reply</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="reply"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2 justify-content-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- / .card-desk-->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
