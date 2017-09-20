<?php
$module_name='訂單';
return [
	'admin'=>[
		'title' => $module_name.'管理',
    	'menu_title' => $module_name.'管理',
        'new_label'=>'新增'.$module_name,
        'new_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
    	'id' => '訂單編號',
    	'price' => '金額',
    	'user_nick_name' => '訂購者',
    	'memo' => '備註',
    	'status' => '付款狀態',
    	'status_0'=>'未付款',
    	'status_1'=>'已付款',
    	'products' => '產品',
        'created_at' => '訂購日期',
    ],
];
