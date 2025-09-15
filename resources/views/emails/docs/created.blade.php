<!DOCTYPE html>
<html>
<head>
    <title>New Document Created</title>
</head>
<body>
    <h1>📄 A new document has been created!</h1>
    <p><strong>Title:</strong> {{ $document->title }}</p>
    <p><strong>Description:</strong> {{ $document->description }}</p>
    <p>Created on: {{ $document->created_at->format('M d, Y H:i') }}</p>
    <p><a href="{{ url('/documents/' . $document->slug) }}">View Document</a></p>
</body>
</html>
