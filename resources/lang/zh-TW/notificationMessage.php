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
        'type' =>'通知類型',
        'type_Announcement'=>'系統公告',
        'type_TermOfService'=>'使用條款變更',
        'type_NewFeature'=>'功能更新',
        'type_NewProduct'=>'新商品通知',
        'type_Promotion'=>'優惠訊息',
        'type_MarketAlert'=>'市場訊息通知',
        'type_StockAlert'=>'個股訊息通知',
        'type_FavoriteStockAlert'=>'我的最愛股',
        'type_ProductReceive'=>'商品贈送',
        'type_PromocodeReceive'=>'優惠券發送',
        'type_Others'=>'其他'
    ]
];
