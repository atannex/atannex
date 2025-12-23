@include('errors.layout', [
'code' => 500,
'headline' => 'Uh-oh!',
'title' => 'Server Error',
'message' => 'Something went wrong on our end. Our team is working to fix it.',
'imageLight' => 'assets/img/theme-img/error.png',
'imageDark' => 'assets/img/theme-img/error.png',
])
