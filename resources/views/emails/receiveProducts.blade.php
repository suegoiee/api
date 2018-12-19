@extends('emails.layout')
@section('title')
	收到贈禮
@endsection
@section('content')
	<p>以下是台股優分析贈送給您的產品：</p>
            <div style="min-height: 200px">
                @foreach($products as $product)
                    <div >
                    	<div>
	                        <div style="color: #AAA;">
	                            產品名稱
	                        </div>
	                        <div style="font-size: 20px;font-weight: bolder;padding-top: 5px">
	                            {{$product->name}}
	                        </div>
                    	</div>
                    	<div style="padding: 20px 0 0 0;">
	                        <div style="color: #AAA;">
	                            使用期限
	                        </div>
	                        <div  style="font-size: 20px;font-weight: bolder;padding-top: 5px">
	                            {{$product->deadline ? date('Y/m/d', strtotime($product->deadline)) :'無期限'}}
	                       	</div>
                   		</div>
                    </div>
                @endforeach
            </div>
    <p style="margin-bottom: 0;">投資順利</p>
@endsection
@section('btn_start')
	<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw')}}" style="text-decoration: none;color: #FFF;">
@endsection
@section('btn_text')
	前往優分析
@endsection
@section('btn_end')
	</a>
@endsection