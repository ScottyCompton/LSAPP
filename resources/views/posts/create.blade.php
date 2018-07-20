@extends('layouts.app')

@section('content')
    <h1>Create Post Posts</h1>
    {!! Form::open(['action'=>'PostsController@store', 'method'=> 'POST']) !!}
    <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Enter Post Title'])}}
        </div>
    <div class="form-group">
        {{Form::label('body', 'Body')}}
        {{Form::textarea('body', '', ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Enter Post Text'])}}
    </div>
    {{Form::submit('Create Post', ['class' => 'btn btn-primary'])}}
    <a href="/posts/" class="btn btn-default">Cancel</a>
    {!! Form::close() !!}
@endsection