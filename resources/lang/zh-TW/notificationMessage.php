<?php
$module_name='通知';
return [
	'admin'=>[
		'menu_title'=> $module_name.'管理',
    	'new_label'=>'新增'.$module_name,
    	'create_title'=>'新增'.$module_name,
        'edit_label'=>'編輯'.$module_name,
        'edit_title'=>'編輯'.$module_name,
    	'id' => '編號',
        'title'=> '標題',
        'content' => '訊息',
        'all_user'=>'通知全會員',
        'user_ids' => '通知對象',
        'product_ids'=> '通知產品使用者',
        'send_email'=>'寄送Email',
        'send_notice'=>'寄送站內通知',
        'expired_user'=>'過期的使用者',
        'non_expired_user'=>'未過期的使用者',
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
        'type_Others'=>'其他',
        'type_MassiveAnnouncement'=>'全站寄信',
        'type_RelatedProduct'=>'產品使用者通知'
    ]
];
