@include('errors.layout', [
'code' => 429,
'headline' => 'Slow Down!',
'title' => 'Too Many Requests',
'message' => 'You have made too many requests in a short period. Please wait a moment and try again.',
'imageLight' => 'assets/img/theme-img/error.png',
'imageDark' => 'assets/img/theme-img/error.png',
])
