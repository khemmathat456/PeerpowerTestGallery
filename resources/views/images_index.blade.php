@extends('layouts.app')

@section('content')
    <div class = "container">

    <h1>Data used: {{ $data_used }}</h1>
    <h3>Type used:
        @foreach($counter as $type => $count)
            <h4>{{ $type }}:{{ $count }}</h4>
        @endforeach
    </h3>
    @foreach ($images as $image)
            <form action="{{ route('images.destroy', [$image->name_unique]) }}" enctype="multipart/form-data" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="submit" class="btn btn-danger delete-user" value="Delete user">
                </div>
            </form>
            <p>This is {{ $image->name }}</p>
        <img src="{{ url($image->path)}}">
    </div>
    @endforeach
@endsection
