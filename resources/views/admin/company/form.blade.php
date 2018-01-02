 <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#company_tab" aria-controls="company" role="tab" data-toggle="tab">{{trans($module_name.'.admin.company_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#event_tab" aria-controls="event" role="tab" data-toggle="tab">{{trans($module_name.'.admin.event_tab')}}</a></li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="company_tab">
        @include('admin.company.company_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="event_tab"> 
        @include('admin.company.event_form')
    </div>
</div>