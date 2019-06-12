<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>File Upload Manager</title>
    <link rel="stylesheet" href="css/app.css" charset="utf-8">
</head>
<body>
    <h1>Data used: {{ $data_used }}</h1>
    <h3>Type used:
        @foreach($counter as $type => $count)
            <h4>{{ $type }}:{{ $count }}</h4>
        @endforeach
    </h3>
    @foreach ($images as $image)
        <div class = "container">
            <form action="{{ route('images.destroy', [$image->name_unique]) }}" enctype="multipart/form-data" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="submit" class="btn btn-danger delete-user" value="Delete user">
                </div>
            </form>
            <p>This is {{ $image->name }}</p>
        </div>
        <img src="{{ url($image->path)}}">
    @endforeach
</body>
</html>
