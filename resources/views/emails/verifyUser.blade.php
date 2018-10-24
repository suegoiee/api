@component('mail::message')
{{$nickname}} 您好，<br>
@component('mail::panel')
請點選已下連結進行驗證：
@component('mail::button', ['url' => url('/auth/verified').'?email='.$email.'&token='.$token, 'color' => 'green'])
驗證信箱
@endcomponent
<a target="_blank" href="{{env('EMAIL_VERIFIED_URL',url('/email/verify'))}}"></a>
@endcomponent
<br>
投資順利<br>
台股優分析  UAnalyze
@endcomponent