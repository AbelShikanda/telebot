@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="h3 mb-4 page-title">Message Information</h2>
                <div class="row mt-5 align-items-center">
                    <div class="col-md-3 text-center mb-5">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset('assets/avatars/face-1.jpg')}}" alt="..." class="avatar-img rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h4 class="mb-1">{{ $messages->user->first_name }}</h4>
                                <p class="small mb-3"><span class="badge badge-dark">{{ $messages->user->username }} {{ $messages->user->last_name }} ({{ $messages->user->user_id }})</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <p class="text-muted"> Message of interest: </p>
                                <p class="text-muted"> {{ $messages->text }} </p>
                            </div>
                            <div class="col">
                                <p class="small mb-0 text-muted">No. of warnings: {{ $messages->user->warning_count }} </p>
                                <p class="small mb-0 text-muted">last warned on: {{ $messages->user->last_warning_at }} </p>
                                <p class="small mb-0 text-muted">Has interacted since: {{ $messages->user->joined_at }} </p>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col">
                        <div class="card mb-4 shadow">
                            <div class="card-body my-n3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-lg bg-light">
                                            <i class="fe fe-phone fe-24 text-primary"></i>
                                        </span>
                                    </div> <!-- .col -->
                                    <div class="col">
                                        <a href="#">
                                            <h3 class="h5 mt-4 mb-1">Chat Information</h3>
                                        </a>
                                        <p class="text-muted"> The type of chat: {{ $messages->chat->type }} </p>
                                        <p class="text-muted"> The Name of the chat: {{ $messages->chat->title }} </p>
                                        <p class="text-muted"> The id No. of the chat: {{ $messages->chat->chat_id }} </p>
                                    </div> <!-- .col -->
                                </div> <!-- .row -->
                            </div> <!-- .card-body -->
                            <div class="card-footer">
                                <a href="" class="d-flex justify-content-between text-muted"><span>Account
                                        Settings</span><i class="fe fe-chevron-right"></i></a>
                            </div> <!-- .card-footer -->
                        </div> <!-- .card -->
                    </div> <!-- .col-md-->
                </div> <!-- .row-->
                <!-- table -->
                <table class="table datatables" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($threads as $thread)
                            <tr>
                                <td>#</td>
                                <td>{{ $thread->text }}</td>
                                <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                        type="button" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="text-muted sr-only">Action</span>
                                    </button>
                                </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
            </div> <!-- /.col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
