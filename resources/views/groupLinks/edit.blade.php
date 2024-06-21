@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">Group links</h2>
                <p class="text-muted">Links for all types of platfoms are allowed.</p>
                @if (count($errors) > 0)
                    <div class="alert alert-danger col-md-8 offset-md-3">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                <div class="card-deck">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Responses form</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('grouplinks.update', $replies->id) }}" method="POST">
                                @csrf
                                @method('Patch')
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Platform</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="platform" id="inputEmail3"
                                            value="{{ $replies->platform }}" placeholder="Enter Keyword">
                                        <small id="emailHelp" class="form-text text-muted">add any platform
                                            of</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3" for="exampleFormControlTextarea1">Group Link</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="link"
                                            placeholder="{{ $replies->link }}"></textarea>
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
