{{$nickname}} 您好，<br>
以下是台股優分析贈送給您的產品，<br>
<table>
    <thead>
        <tr>
            <td>產品名稱</td><td>使用期限</td>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{$product->name}}</td><td>{{$product->deadline}}</td>
            </tr>
        @endforeach
    </tbody>
</table>


投資順利<br>
台股優分析  UAnalyze