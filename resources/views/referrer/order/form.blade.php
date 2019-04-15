<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item">
    	<a class="nav-link active" href="#order_tab" aria-controls="order" role="tab" data-toggle="tab">{{trans($module_name.'.admin.order_tab')}}
    	</a>
    </li>
    <li role="presentation" class="nav-item">
    	<a class="nav-link" href="#invoice_tab" aria-controls="invoice" role="tab" data-toggle="tab">{{trans($module_name.'.admin.invoice_tab')}}
    	</a>
    </li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="order_tab">
        @include('admin.order.order_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="invoice_tab">
        @include('admin.order.invoice_form')
    </div>
</div>