<?php
$module_name='文章';
return [
	'admin'=>[
		'menu_title'=> $module_name.'管理',
    	'title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,

    	'id'=>'編號',
    	'title'=>'標題',
    	'top'=>'置頂',
        'content'=>'內容',
    	'status'=>'狀態',
    	'status_0'=>'未發佈',
    	'status_1'=>'發佈',
        'tags'=>'標籤',
        'posted_at' => '發佈時間',
	],
    'front'=>'無此文章',
];
