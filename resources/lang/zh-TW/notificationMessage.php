<?php
$module_name='通知';
return [
	'admin'=>[
		'title'=> $module_name.'管理',
    	'menu_title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
    	'id' => '編號',
        'content' => '訊息',
        'all_user'=>'通知全會員',
        'user_ids' => '通知對象',
        'send_email'=>'寄送Email',
        'created_at' => $module_name.'時間',
    ],
];
