@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 150px">
        <div class="pt-3">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <table class="table">
            <a href="{{ route('posts.create') }}" class="btn btn-secondary">Create replies</a>
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">keyword</th>
                    <th scope="col">response</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <th scope="row">1</th>
                        <td><img src="{{ asset('/storage/posts/'.$post->image) }}" alt="" style="width: 50px;"></td>
                        <td>{{ $post->caption }}</td>
                        <td>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Edit replies</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
