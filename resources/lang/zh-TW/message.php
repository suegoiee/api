<?php
$module_name='留言';
return [
	'admin'=>[
		'title'=> $module_name.'管理',
    	'menu_title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
    	'id' => '編號',
    	'name' => '姓名',
        'email' => 'E-mail',
        'category' => '類別',
        'content' => '訊息',
        'status' => '狀態',
        'status_0' => '未讀',
        'status_1' => '已讀',
        'created_at' => '留言時間',
    ],
];
