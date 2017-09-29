<?php
return [
    'ServiceURL' => env('ALLPAY_SERVICE_URL','https://payment-stage.allpay.com.tw/Cashier/AioCheckOut/V4'),
    'HashKey'    => env('ALLPAY_HASH_KEY','5294y06JbISpM5x9'),
    'HashIV'     => env('ALLPAY_HASH_IV','v77hoKGq4kWxNNIS'),
    'MerchantID' => env('ALLPAY_MERCHANT_ID','2000132'),
];