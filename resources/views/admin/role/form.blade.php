<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#expert_tab" aria-controls="expert" role="tab" data-toggle="tab">{{trans($module_name.'.admin.expert_tab')}}</a></li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="expert_tab">
        @include('admin.role.role_form')
    </div>
</div>