<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#physical_course_tab" aria-controls="physical_course" role="tab" data-toggle="tab">{{trans($module_name.'.admin.physical_course_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#host_tab" aria-controls="host" role="tab" data-toggle="tab">{{trans($module_name.'.admin.host_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#suitable_tab" aria-controls="suitable" role="tab" data-toggle="tab">{{trans($module_name.'.admin.suitable_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#electric_ticket_tab" aria-controls="electric_ticket" role="tab" data-toggle="tab">{{trans($module_name.'.admin.electric_ticket_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#image_tab" aria-controls="image" role="tab" data-toggle="tab">{{trans($module_name.'.admin.image_tab')}}</a></li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="physical_course_tab">
        @include('admin.physical_course.physical_course_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="host_tab">
        @include('admin.physical_course.host_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="suitable_tab"> 
        @include('admin.physical_course.suitable_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="electric_ticket_tab"> 
        @include('admin.physical_course.electric_ticket_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="image_tab"> 
        @include('admin.physical_course.image_form')
    </div>
</div>