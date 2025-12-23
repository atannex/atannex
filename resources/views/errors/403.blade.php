@include('errors.layout', [
'code' => 403,
'headline' => 'Access Denied',
'title' => 'Forbidden',
'message' => 'You do not have permission to access this resource.',
'imageLight' => 'assets/img/theme-img/error.png',
'imageDark' => 'assets/img/theme-img/error.png',
])
