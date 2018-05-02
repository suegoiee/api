@component('mail::message')
{{$nickname}} 您好，<br>
以下是台股優分析贈送給您的優惠券，<br>
@component('mail::panel')
    <table style="width: 100%">
        <tbody>
            @foreach($promocodes as $promocode)
                <tr>
                    <td>優惠券名稱</td><td>{{$promocode->name}}</td>
                </tr>
                <tr>
                   <td>優惠碼</td> <td style="font-weight: bold;font-size: large;">{{$promocode->code}}</td>
                </tr>
                <tr>
                    <td>使用期限</td><td>{{$promocode->deadline}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endcomponent
<br>
投資順利<br>
台股優分析  UAnalyze
@endcomponent