@include('errors.layout', [
'code' => 402,
'headline' => 'Payment Required',
'title' => 'Subscription Needed',
'message' => 'This feature requires an active subscription. Please upgrade your plan to continue.',
'imageLight' => 'assets/img/theme-img/error.png',
'imageDark' => 'assets/img/theme-img/error.png',
'buttonUrl' => route('pricing'),
'buttonText' => 'View Plans',
'buttonIcon' => 'credit-card',
])
