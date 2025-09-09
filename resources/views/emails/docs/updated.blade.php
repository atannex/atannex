<!DOCTYPE html>
<html>
<head>
    <title>Document Updated</title>
</head>
<body>
    <h1>✏️ A document has been updated</h1>
    <p><strong>Title:</strong> {{ $document->title }}</p>
    <p><strong>Description:</strong> {{ $document->description }}</p>
    <p>Last updated on: {{ $document->updated_at->format('M d, Y H:i') }}</p>
    <p><a href="{{ url('/documents/' . $document->slug) }}">View Updated Document</a></p>
</body>
</html>
