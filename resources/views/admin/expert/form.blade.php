<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item"><a class="nav-link active" href="#expert_tab" aria-controls="expert" role="tab" data-toggle="tab">{{trans($module_name.'.admin.expert_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#book_tab" aria-controls="host" role="tab" data-toggle="tab">{{trans($module_name.'.admin.book_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#avatar_tab" aria-controls="avatar" role="tab" data-toggle="tab">{{trans($module_name.'.admin.avatar_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#experience_tab" aria-controls="experience" role="tab" data-toggle="tab">{{trans($module_name.'.admin.experience_tab')}}</a></li>
    <li role="presentation" class="nav-item"><a class="nav-link" href="#interview_tab" aria-controls="suitable" role="tab" data-toggle="tab">{{trans($module_name.'.admin.interview_tab')}}</a></li>
</ul>
<div class="tab-content py-3">
    <div role="tabpanel" class="tab-pane fade show active" id="expert_tab">
        @include('admin.expert.expert_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="avatar_tab">
        @include('admin.expert.avatar_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="experience_tab">
        @include('admin.expert.experience_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="book_tab">
        @include('admin.expert.book_form')
    </div>
    <div role="tabpanel" class="tab-pane fade" id="interview_tab"> 
        @include('admin.expert.interview_form')
    </div>
</div>