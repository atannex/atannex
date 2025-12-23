@include('errors.layout', [
'code' => 401,
'headline' => 'Oops!',
'title' => 'Unauthorized Access',
'message' => 'You must be logged in to access this page.',
'imageLight' => 'assets/img/theme-img/error.png',
'imageDark' => 'assets/img/theme-img/error.png',
'buttonUrl' => route('login'),
'buttonText' => 'Login',
'buttonIcon' => 'sign-in',
])
