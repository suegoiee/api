<?php
return [
    'ServiceURL' => env('ECPAY_SERVICE_URL','https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5'),
    'InvoiceURL' => env('ECPAY_INVOICE_URL','https://einvoice-stage.ecpay.com.tw'),
    'HashKey'    => env('ECPAY_HASH_KEY','5294y06JbISpM5x9'),
    'HashIV'     => env('ECPAY_HASH_IV','v77hoKGq4kWxNNIS'),
    'InvoiceHashKey'=>env('ECPAY_INVOICE_HASH_KEY','ejCk326UnaZWKisg'),
    'InvoiceHashIV'=>env('ECPAY_INVOICE_HASH_IV','q9jcZX8Ib9LM8wYk'),
    'MerchantID' => env('ECPAY_MERCHANT_ID','2000132'),
];