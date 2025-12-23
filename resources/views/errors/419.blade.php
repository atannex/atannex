@include('errors.layout', [
'code' => 419,
'headline' => 'Session Expired',
'title' => 'Please Refresh',
'message' => 'Your session has expired for security reasons. Please refresh the page or log in again.',
'imageLight' => 'assets/img/theme-img/error.png',
'imageDark' => 'assets/img/theme-img/error.png',
'buttonUrl' => url()->previous(),
'buttonText' => 'Refresh Page',
'buttonIcon' => 'redo',
])
