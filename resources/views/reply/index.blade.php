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
            <a href="{{ route('replies.create') }}" class="btn btn-secondary">Create replies</a>
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">keyword</th>
                    <th scope="col">response</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($replies as $reply)
                    <tr>
                        <th scope="row">1</th>
                        <td>{{ $reply->keyword }}</td>
                        <td>{{ $reply->response }}</td>
                        <td>
                            <a href="{{ route('replies.edit', $reply->id) }}" class="btn btn-warning">Edit replies</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection