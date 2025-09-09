<!DOCTYPE html>
<html>
<head>
    <title>Document Deleted</title>
</head>
<body>
    <h1>❌ A document has been deleted</h1>
    <p><strong>Title:</strong> {{ $document->title }}</p>
    <p><strong>Description:</strong> {{ $document->description }}</p>
    <p>Deleted on: {{ now()->format('M d, Y H:i') }}</p>
</body>
</html>
