@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        <ul class="list-group">
        @foreach($posts as $post) 
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <img src="/storage/cover_images/{{$post->cover_image}}" style="width:100%;height:auto;" />
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small>Written On {{$post->created_at}} by {{$post->user->name}}</small>
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
        {{$posts->links()}}
    @else
        <p>No Posts Found</p>
    @endif
@endsection