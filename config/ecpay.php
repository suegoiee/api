<?php
return [
    'ServiceURL' => env('ECPAY_SERVICE_URL','https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5'),
    'HashKey'    => env('ECPAY_HASH_KEY','5294y06JbISpM5x9'),
    'HashIV'     => env('ECPAY_HASH_IV','v77hoKGq4kWxNNIS'),
    'MerchantID' => env('ECPAY_MERCHANT_ID','2000132'),
];