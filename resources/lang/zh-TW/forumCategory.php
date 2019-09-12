<?php
$module_name='論壇文章分類';
return [
	'admin'=>[
		'menu_title'=> $module_name.'管理',
    	'title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
		'edit_title'=>'編輯'.$module_name,
		'category_tab'=>$module_name,
		'category_product_tab'=>$module_name.'/產品連結',
		'name'=>$module_name.'名稱',

		'category'=>'文章分類',
		'product'=>'產品',
    	'id'=>'編號',
    	'title'=>'標題',
        'slug'=>'網址代號',
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
