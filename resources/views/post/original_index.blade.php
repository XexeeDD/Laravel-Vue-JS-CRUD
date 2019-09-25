@extends('layouts.main')

@section('title') Posts @endsection

@section('main-content')
    <h1>All Posts</h1>
    <hr>
    @foreach($posts as $key=>$value)
        <h3>{{ $value->title }}</h3>
        <p>{{ $value->description }}</p>
    @endforeach

    {{ $posts->render() }}
@endsection