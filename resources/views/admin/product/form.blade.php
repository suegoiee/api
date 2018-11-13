 <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#product_tab" aria-controls="product" role="tab" data-toggle="tab">{{trans($module_name.'.admin.product_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#price_tab" aria-controls="price" role="tab" data-toggle="tab">{{trans($module_name.'.admin.price_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#avatar_tab" aria-controls="avatar" role="tab" data-toggle="tab">{{trans($module_name.'.admin.avatar_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#faq_tab" aria-controls="faq" role="tab" data-toggle="tab">{{trans($module_name.'.admin.faq_tab')}}</a></li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="product_tab">
        @include('admin.product.product_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="price_tab">
        @include('admin.product.price_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="avatar_tab"> 
        @include('admin.product.avatar_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="faq_tab"> 
        @include('admin.product.faq_form')
    </div>
</div>