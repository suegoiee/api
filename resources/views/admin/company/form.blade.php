 <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item">
    	<a class="nav-link active" href="#company_tab" aria-controls="company" role="tab" data-toggle="tab">
    		{{trans($module_name.'.admin.company_tab')}}
    	</a>
    </li>
    <li role="presentation" class="nav-item">
    	<a class="nav-link" href="#product_tab" aria-controls="product" role="tab" data-toggle="tab">
			{{trans($module_name.'.admin.product_tab')}}
   		</a>
   	</li>
   	<li role="presentation" class="nav-item">
    	<a class="nav-link" href="#area_tab" aria-controls="area" role="tab" data-toggle="tab">
			{{trans($module_name.'.admin.area_tab')}}
   		</a>
   	</li>
   	<li role="presentation" class="nav-item">
    	<a class="nav-link" href="#supplier_tab" aria-controls="supplier" role="tab" data-toggle="tab">
			{{trans($module_name.'.admin.supplier_tab')}}
   		</a>
   	</li>
   	<li role="presentation" class="nav-item">
    	<a class="nav-link" href="#customer_tab" aria-controls="customer" role="tab" data-toggle="tab">
			{{trans($module_name.'.admin.customer_tab')}}
   		</a>
   	</li>
   	<li role="presentation" class="nav-item">
        <a class="nav-link" href="#local_related_tab" aria-controls="local_related" role="tab" data-toggle="tab">
        {{trans($module_name.'.admin.local_related_tab')}}
        </a>
   	</li>
    <li role="presentation" class="nav-item">
      <a class="nav-link" href="#foreign_related_tab" aria-controls="foreign_related" role="tab" data-toggle="tab">
      {{trans($module_name.'.admin.foreign_related_tab')}}
      </a>
    </li>
    <li role="presentation" class="nav-item">
    	<a class="nav-link" href="#event_tab" aria-controls="event" role="tab" data-toggle="tab">
    		{{trans($module_name.'.admin.event_tab')}}
    	</a>
    </li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="company_tab">
        @include('admin.company.company_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="product_tab"> 
        @include('admin.company.product_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="area_tab"> 
        @include('admin.company.area_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="supplier_tab"> 
        @include('admin.company.supplier_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="customer_tab"> 
        @include('admin.company.customer_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="local_related_tab"> 
        @include('admin.company.local_related_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="foreign_related_tab"> 
        @include('admin.company.foreign_related_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="event_tab"> 
        @include('admin.company.event_form')
    </div>
</div>