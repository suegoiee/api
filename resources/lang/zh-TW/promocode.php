<?php
$module_name='優惠卷';
return [
	'admin'=>[
		'title' => $module_name.'管理',
    	'menu_title' => $module_name.'管理',
        'new_label'=>'新增'.$module_name,
        'import_label'=>'匯入'.$module_name,
        'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,

    	'id' => '編號',
    	'name' => '名稱',
    	'code' => '優惠碼',
        'user_name' => '擁有者',
    	'offer' => '折扣',
        'deadline' => '使用期限',
        'user_id'=>'擁有者',
        'user'=>'擁有者',
        'used_at'=>'已使用',
        'type'=>'類型',
        'type_0'=>'非指名優惠券',
        'type_1'=>'指名優惠券',

        'promocodefile'=>'優惠卷CSV'
    ],
];
