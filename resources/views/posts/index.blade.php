@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-2 page-title">Posts</h2>
                <a href="{{ route('posts.create') }}" class="btn btn-secondary">Create Posts</a>
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
                                            <th scope="col">Image</th>
                                            <th scope="col">caption</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($posts as $post)
                                            <tr>
                                                <td>#</td>
                                                <td><img src="{{ asset('storage/app/public/posts/'.$post->image) }}" alt="" style="width: 50px;"></td>
                                                @php
                                                    $words = explode(' ', $post->caption);
                                                    $excerpt = implode(' ', array_slice($words, 0, 1));
                                                @endphp
                                                <td>{{ $excerpt }}</td>
                                                <td><button class="btn btn-sm dropdown-toggle more-horizontal"
                                                        type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <span class="text-muted sr-only">Action</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#">View</a>
                                                        <a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a>
                                                        <a class="dropdown-item" href="#">Remove</a>
                                                        <a class="dropdown-item" href="#">Assign</a>
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
