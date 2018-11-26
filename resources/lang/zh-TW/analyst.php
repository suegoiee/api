<?php 
	return [
		'admin'=>[
			'title'=>'分析師管理',
			'menu_title'=>'分析師管理',
			'create_title'=>'新增分析師',
			'edit_title'=>'編輯分析師',
			'new_label'=>'新增分析師',
			'name'=>'名稱',
			'email'=>'E-mail',
			'product_name'=> '產品名稱',
	        'product_id'=>'產品編號',
	        'product_price'=>'產品價格',
	        'handle_fee'=>'金流手續費',
	        'platform_fee'=>'平台手續費',
	        'order_price'=>'實際付款金額',
	        'id' => '編號',
	        'no' => '訂單編號',
	        'price' => '訂單金額',
	        'user_nickname' => '訂購者',
	        'memo' => '備註',
	        'status' => '付款狀態',
	        'status_0'=>'未付款',
	        'status_1'=>'已付款',
	        'status_2'=>'付款失敗',
	        'status_3'=>'已取號',
	        'status_4'=>'取號失敗',
	        'free_0'=>'免付費',
	        'products' => '產品',
	        'created_at' => '訂購日期',
	        'paymentType'=>'付款方式',
	        'paymentType_'=>'免費',
	        'paymentType_credit'=>'信用卡',
	        'paymentType_webatm'=>'網路ATM',
	        'paymentType_atm'=>'ATM櫃員機',
	        'paymentType_cvs'=>'超商代碼',
	        'paymentType_barcode'=>'超商條碼',
	        'use_invoice' => '使用發票',
	        'use_invoice_0' => '不使用',
	        'use_invoice_1' => '使用',
	        'invoice_type' => '發票類型',
	        'invoice_type_0' => '捐贈',
	        'invoice_type_1' => '二聯',
	        'invoice_type_2' => '三聯',
	        'invoice_name' => '姓名',
	        'invoice_phone' => '連絡電話',
	        'invoice_address' => '地址',
	        'invoice_title' => '抬頭',
	        'company_id' => '統一編號',
			'id'=>'編號',
			'no'=>'客戶識別碼',
			'password'=>'密碼',
			'updated_at'=>'最後登入時間',
			'password_notice'=>'輸入密碼時，將會更新密碼。',
			'products'=>'分析師產品',
			'statement_no'=>'對帳單號碼',
			'year_month'=>'銷售日期',
			'detail'=>'明細',
			'grant_new_label'=>'新增撥款',
			'grant_menu_title'=>'撥款管理',
			'grant_detail_menu_title'=>'產品銷售明細',
			'grantCreate_title'=>'新增撥款',
			'grantEdit_title'=>'編輯撥款',
			'detail_page'=>'產品銷售明細',
			'detail_price'=>'本月銷售額',
			'detail_handle_fee'=>'金流手續費',
			'detail_platform_fee'=>'平台服務費',
			'detail_net_commission'=>'本月佣金淨額',
			'detail_income_tax'=>'代扣執行業務所得',
			'detail_second_generation_nhi'=>'二代健保',
			'detail_interbank_remittance_fee'=>'跨行匯款手續費',
			'detail_net_sales_payment'=>'本月銷售給付淨額',
			'detail_transfer_amount'=>'本月應轉帳金額',
			'detail_exrta_amount'=>'其他款項',
			
			'detail_income_tax_q'=>'所得逾2萬元時，我們會代繳10%佣金所得，明年再傳扣繳憑單給您)',
			'detail_second_generation_nhi_q'=>'逾兩萬元1.91%',

		],
		'date'=>'日期',
		'date_q'=>'遇假日順延一日',
		'detail_title'=>'產品銷售明細',
		'detail_promocode_title'=>'優惠券使用',
		'grant_title'=>'銷售撥款列表',
		'grant_detail_title'=>'撥款明細',
		'grant_detail_table'=>'銷售撥款明細表',
		'menu_title' => '銷售列表',
		'menu_promocode_title' => '優惠券列表',
        'product_name'=> '產品名稱',
        'product_id'=>'產品編號',
        'product_price'=>'產品價格',
        'handle_fee'=>'金流手續費',
        'platform_fee'=>'平台手續費',
        'order_price'=>'實際付款金額',
        'id' => '編號',
		'no'=>'客戶識別碼',
        'price' => '訂單金額',
        'user_nickname' => '訂購者',
        'memo' => '備註',
        'status' => '付款狀態',
        'status_0'=>'未付款',
        'status_1'=>'已付款',
        'status_2'=>'付款失敗',
        'status_3'=>'已取號',
        'status_4'=>'取號失敗',
        'free_0'=>'免付費',
        'products' => '產品',
        'created_at' => '訂購日期',
        'paymentType'=>'付款方式',
        'paymentType_'=>'免費',
        'paymentType_credit'=>'信用卡',
        'paymentType_webatm'=>'網路ATM',
        'paymentType_atm'=>'ATM櫃員機',
        'paymentType_cvs'=>'超商代碼',
        'paymentType_barcode'=>'超商條碼',
        'use_invoice' => '使用發票',
        'use_invoice_0' => '不使用',
        'use_invoice_1' => '使用',
        'invoice_type' => '發票類型',
        'invoice_type_0' => '捐贈',
        'invoice_type_1' => '二聯',
        'invoice_type_2' => '三聯',
        'invoice_name' => '姓名',
        'invoice_phone' => '連絡電話',
        'invoice_address' => '地址',
        'invoice_title' => '抬頭',
        'company_id' => '統一編號',
        'remmit'=>'匯款',
        'name'=>'客戶名稱',
        'pay_price'=>'應付金額',
		'auth'=>[
			'login_title'=>'分析師登入',

		    'name_label'=>'姓名',
		    'email_label'=>'信箱',
		    'password_label'=>'密碼',

		    'login'=>'登入',
		    'logout'=>'登出',
		    'failed' => '密碼錯誤!',
		    'throttle' => '登入次數過多，請於 :seconds 秒後再次嘗試登入。',
		    'password_reset_success' =>'密碼重設成功',
		    'permission_denied' =>'尚無存取權限。',
		    'old_password_error'=>'原密碼錯誤!',
		    'invalid_credential'=>'The user credentials were incorrect.',
		],
		'dashboard'=>[
			'product_title'=>'上架中產品',
			'client_title'=>'購買的使用者',
			'order_title'=>'本月訂單數',
			'promocode_title'=>'優惠券使用數',
			'doller'=>'元',
			'sales_amount_title'=>'*尚未扣稅以及手續費',
			'account_title'=>'本月產品銷售額'
		],
	];