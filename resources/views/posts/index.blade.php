@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        <ul class="list-group">
        @foreach($posts as $post) 
            <li class="list-group-item">
            <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                <small>Written On {{$post->created_at}}</small>
            </li>
        @endforeach
        </ul>
        {{$posts->links()}}
    @else
        <p>No Posts Found</p>
    @endif
@endsection