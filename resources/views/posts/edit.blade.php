@extends('layouts.app')

@section('content')
    <h1>Edit Post Posts</h1>
    {!! Form::open(['action'=> ['PostsController@update', $post->id], 'method'=> 'POST']) !!}
    <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Enter Post Title'])}}
        </div>
    <div class="form-group">
        {{Form::label('body', 'Body')}}
        {{Form::textarea('body', $post->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Enter Post Text'])}}
    </div>
    {{Form::submit('Update Post', ['class' => 'btn btn-primary'])}}
    <a href="/posts/{{$post->id}}" class="btn btn-default">Cancel</a>
    {{Form::hidden('_method', 'PUT')}}
    {!! Form::close() !!}
@endsection