@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-2 page-title">Replies</h2>
                <a href="{{ route('replies.create') }}" class="btn btn-secondary">Create Replies</a>
                <div class="pt-3">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                </div>
                <div class="row my-4">
                    <!-- Small table -->
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <!-- table -->
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th scope="col">keyword</th>
                                            <th scope="col">response</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($replies as $reply)
                                            <tr>
                                                <td>#</td>
                                                <td>{{ $reply->keyword }}</td>
                                                @php
                                                    $words = explode(' ', $reply->response);
                                                    $excerpt = implode(' ', array_slice($words, 0, 1));
                                                @endphp
                                                <td>{{ $excerpt }}</td>
                                                <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                        type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <span class="text-muted sr-only">Action</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ route('replies.edit', $reply->id) }}">Edit</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('replies.destroy', $reply->id) }}"
                                                            onclick="event.preventDefault();
                                                            document.getElementById('destroy').submit();"
                                                            >
                                                            {{ __(' Remove') }}
                                                        </a>

                                                        <form id="destroy"
                                                            action="{{ route('replies.destroy', $reply->id) }}"
                                                            method="POST" class="d-none"
                                                            onsubmit="return confirm('Are you sure you want to delete this reply?');"
                                                        >
                                                        @csrf
                                                        @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                        @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- end section -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
