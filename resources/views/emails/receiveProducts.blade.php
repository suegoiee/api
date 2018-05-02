@component('mail::message')
{{$nickname}} 您好，<br>
以下是台股優分析贈送給您的產品，<br>
<br>
<table style="border:1px solid #777; width: 100%">
    <thead>
        <tr>
            <th style="border:1px solid #777;">產品名稱</th><th style="border:1px solid #777;">使用期限</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td style="border:1px solid #777;text-align: center;">{{$product->name}}</td><td style="border:1px solid #777;text-align: center;">{{$product->deadline ? $product->deadline :'無期限'}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
投資順利<br>
台股優分析  UAnalyze
@endcomponent