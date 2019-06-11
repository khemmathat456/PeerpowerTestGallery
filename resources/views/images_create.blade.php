<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>File Upload Manager</title>
    <link rel="stylesheet" href="css/app.css" charset="utf-8">
</head>
<body>
<div class = "container">
    <h3>File Upload Manager</h3>
    <form action="{{ route('images.store') }}" enctype="multipart/form-data" method="post">
        {{ csrf_field() }}
        <div class = "form-group"><input type="file" class = "form-control" name="fileUpload"></div>
        <div class = "form-group"><input type="submit" class = "btn btn-primary" value="Upload new file"></div>
    </form>
</div>
</body>
</html>
