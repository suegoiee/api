<?php
$module_name='訂單';
return [
	'admin'=>[
		'title' => $module_name.'管理',
    	'menu_title' => $module_name.'管理',
        'new_label'=>'新增'.$module_name,
        'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
        'order_tab' =>'訂購資訊',
        'invoice_tab' =>'發票資訊',

    	'id' => '編號',
        'no' => '訂單編號',
    	'price' => '金額',
    	'user_nickname' => '訂購者',
    	'memo' => '備註',
    	'status' => '付款狀態',
    	'status_0'=>'未付款',
    	'status_1'=>'已付款',
        'status_2'=>'付款失敗',
        'status_3'=>'已取號',
        'status_4'=>'取號失敗',
        'free_0'=>'免付費',
    	'products' => '產品',
        'created_at' => '訂購日期',
        'paymentType'=>'付款方式',
        'paymentType_'=>'免費',
        'paymentType_credit'=>'信用卡',
        'paymentType_webatm'=>'網路ATM',
        'paymentType_atm'=>'ATM櫃員機',
        'paymentType_cvs'=>'超商代碼',
        'paymentType_barcode'=>'超商條碼',
        'use_invoice' => '使用發票',
        'use_invoice_0' => '不使用',
        'use_invoice_1' => '使用',
        'invoice_type' => '發票類型',
        'invoice_type_0' => '捐贈',
        'invoice_type_1' => '二聯',
        'invoice_type_2' => '三聯',
        'invoice_name' => '姓名',
        'invoice_phone' => '連絡電話',
        'invoice_address' => '地址',
        'invoice_title' => '抬頭',
        'company_id' => '統一編號',
    ],
    'analyst'=>[
        'menu_title' => '銷售列表',
        'detail_title'=> '產品銷售明細',
        'grant_title'=>'銷售撥款列表',
        'grant_detail_title'=>'撥款明細',
        'grant_detail_table'=>'銷售撥款明細表',
        'product_name'=> '產品名稱',
        'product_id'=>'產品編號',
        'product_price'=>'產品價格',
        'handle_fee'=>'金流手續費',
        'platform_fee'=>'平台手續費',
        'order_price'=>'實際付款金額',
        'statement_no'=>'對帳單號碼',
        'year_month'=>'銷售日期',
        'id' => '編號',
        'no' => '訂單編號',
        'price' => '訂單金額',
        'user_nickname' => '訂購者',
        'memo' => '備註',
        'status' => '付款狀態',
        'status_0'=>'未付款',
        'status_1'=>'已付款',
        'status_2'=>'付款失敗',
        'status_3'=>'已取號',
        'status_4'=>'取號失敗',
        'free_0'=>'免付費',
        'products' => '產品',
        'created_at' => '訂購日期',
        'paymentType'=>'付款方式',
        'paymentType_'=>'免費',
        'paymentType_credit'=>'信用卡',
        'paymentType_webatm'=>'網路ATM',
        'paymentType_atm'=>'ATM櫃員機',
        'paymentType_cvs'=>'超商代碼',
        'paymentType_barcode'=>'超商條碼',
        'use_invoice' => '使用發票',
        'use_invoice_0' => '不使用',
        'use_invoice_1' => '使用',
        'invoice_type' => '發票類型',
        'invoice_type_0' => '捐贈',
        'invoice_type_1' => '二聯',
        'invoice_type_2' => '三聯',
        'invoice_name' => '姓名',
        'invoice_phone' => '連絡電話',
        'invoice_address' => '地址',
        'invoice_title' => '抬頭',
        'company_id' => '統一編號',
    ],
];
