 <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#user_tab" aria-controls="user" role="tab" data-toggle="tab">{{trans($module_name.'.admin.user_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#product_tab" aria-controls="product" role="tab" data-toggle="tab">{{trans($module_name.'.admin.product_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#order_tab" aria-controls="order" role="tab" data-toggle="tab">{{trans($module_name.'.admin.order_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#laboratory_tab" aria-controls="laboratory" role="tab" data-toggle="tab">{{trans($module_name.'.admin.laboratory_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#favorite_tab" aria-controls="favorite" role="tab" data-toggle="tab">{{trans($module_name.'.admin.favorite_tab')}}</a></li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="user_tab">
        @include('admin.user.user_detail')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="product_tab"> 
        @include('admin.user.product_detail')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="order_tab">
        @include('admin.user.order_detail')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="laboratory_tab">
        @include('admin.user.laboratory_detail')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="favorite_tab">
        @include('admin.user.favorite_detail')
    </div>
</div>