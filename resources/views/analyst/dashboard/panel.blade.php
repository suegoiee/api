<div class="row">
	<div class="card text-center" id="card-2">
		<h3 class="card-header">{{trans('analyst.dashboard.account_title')}}</h3>
		
		<div class="card-body">
			<span class="number">{{ number_format($sales_amounts)}}</span> 
			<span class="doller">{{trans('analyst.dashboard.doller')}}</span>
			<div class=" price_label">({{trans('analyst.dashboard.sales_amount_title')}})</div>
		</div>
		
	</div>
</div>