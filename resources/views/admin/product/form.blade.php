 <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#product_tab" aria-controls="product" role="tab" data-toggle="tab">{{trans($module_name.'.admin.product_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#avatar_tab" aria-controls="avatar" role="tab" data-toggle="tab">{{trans($module_name.'.admin.avatar_tab')}}</a></li>
    <!--<li role="presentation" class="nav-item"><a class="nav-link" href="#avatar_small_tab" aria-controls="avatar_small" role="tab" data-toggle="tab">{{trans($module_name.'.admin.avatar_small_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#avatar_detail_tab" aria-controls="avatar_detail" role="tab" data-toggle="tab">{{trans($module_name.'.admin.avatar_detail_tab')}}</a></li>-->
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="product_tab">
        @include('admin.product.product_form')
    </div>
     <div role="tabpanel" class="tab-pane fade" id="avatar_tab"> 
        @include('admin.product.avatar_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="avatar_small_tab"> 
        @include('admin.product.avatar_small_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="avatar_detail_tab">
        @include('admin.product.avatar_detail_form')
    </div>
</div>