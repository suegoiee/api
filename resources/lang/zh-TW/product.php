<?php
$module_name='產品';
return [
	'admin'=>[
		'menu_title'=> $module_name.'管理',
    	'title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
        'assigned_label'=>'贈送'.$module_name,
        'assignedView_title'=>'贈送'.$module_name,
        'sorted_label'=>$module_name.'排序',
        'sortedView_title'=>$module_name.'排序',
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
        'product_tab'=>$module_name.'資料',
        'price_tab'=>'購買方案',
        'avatar_tab'=>'產品圖片',
        'faq_tab'=>'FAQ',

        'month'=>'月',
        'active'=>'啟用方案',
        'active_0'=>'不啟用',
        'active_1'=>'啟用',
        'expiration_0'=>'無期限',
        'plans'=>'購買方案',
        'expiration_1'=>'一個月',
        'expiration_6'=>'半年',
        'expiration_12'=>'一年',
        'unit'=>'元',
    	'id'=>'編號',
    	'name'=>'名稱',
    	'type'=>'類型',
    	'type_single'=>'單一',
    	'type_collection'=>'組合',
    	'price'=>'價格',
    	'model'=>'對應API',
        'column'=>'欄位',
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
        'deadline'=>'使用期限',
        'installed'=>'安裝',
        'installed_0'=>'未安裝',
        'installed_1'=>'已安裝',
        'faq'=>'FAQ',
        'faq_q'=>'Q',
        'faq_a'=>'A',
        'assigned_products'=>'選擇欲贈送的產品',
        'assigned_users'=>'選擇贈與的使用者',
        'send_email'=>'是否寄信',
	],
    'collection_cant_del' =>'無法個別刪除。',
    'cant_no_products' =>'需至少有一項產品。'
];
