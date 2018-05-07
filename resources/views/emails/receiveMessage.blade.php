@component('mail::message')
{{$nickname}} 您好，<br>
@component('mail::panel')
{!! $contents !!}
@endcomponent
<br>
投資順利<br>
台股優分析  UAnalyze
@endcomponent