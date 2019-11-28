<?php  
	$module_name='公告';
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
	        'type'=>'類別',
	        'type_news'=>'最新消息',
	        'type_system'=>'系統公告',
	        'content'=>'內容',
	    	'status'=>'狀態',
	    	'status_0'=>'草稿',
	    	'status_1'=>'發佈',
	        'tags'=>'標籤',
	        'created_at'=>'日期'
		]

	];