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
        'free_0'=>'免付費',
    	'products' => '產品',
        'created_at' => '訂購日期',

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
