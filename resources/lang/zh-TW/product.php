<?php
$module_name='產品';
return [
	'admin'=>[
		'menu_title'=> $module_name.'管理',
    	'title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
        'product_tab'=>$module_name.'資料',
        'avatar_tab'=>'產品圖片',

    	'id'=>'編號',
    	'name'=>'名稱',
    	'type'=>'類型',
    	'type_single'=>'單一',
    	'type_collection'=>'組合',
    	'price'=>'價格',
    	'model'=>'分析模組',
    	'info_short'=>'簡介',
    	'info_more'=>'詳細介紹',
        'expiration'=>'使用期限',
    	'status'=>'狀態',
    	'status_0'=>'下架',
    	'status_1'=>'上架',
        'tags'=>'標籤',
        'created_at' => '訂購時間',
        'collections'=>'產品組合',
        'avatar_small'=>'圖片',
        'avatar_detail'=>'圖片集',
	],
    'collection_cant_del' =>'無法個別刪除。'
];
