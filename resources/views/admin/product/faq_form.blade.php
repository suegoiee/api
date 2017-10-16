    <div  class="form-group row">
        <div class="col-12 text-center">
            <button class="btn btn-success" id="new_faq_btn"><span class="oi oi-plus"></span></button>
        </div>
    </div>
<div id="faqs">
@forelse($data->faqs as $key => $faq)
    <div class="form-group row">
        <div class="col-10">
            <div class="col-sm-12">
                <div class="row">
                    <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.faq_q')}}</label>
                    <div class="col-sm-10">
                        <textarea class="form-control ckeditor" id="faq_q_{{$key}}" name="faqs[{{$key}}][question]" placeholder="{{trans($module_name.'.admin.faq_q')}}">{{$faq->question}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-1">
                    </div>
                    <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.faq_a')}}</label>
                    <div class="col-sm-9">
                        <textarea class="form-control ckeditor" id="faq_a_{{$key}}" name="faqs[{{$key}}][answer]" placeholder="{{trans($module_name.'.admin.faq_a')}}">{{$faq->answer}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2 text-center">
            <input type="hidden" name="faqs[{{$key}}][id]" value="{{$faq->id}}">
            <button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>
        </div>
    </div>
@empty
    <div class="form-group row">
        <div class="col-10">
            <div class="col-sm-12">
                <div class="row">
                    <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.faq_q')}}</label>
                    <div class="col-sm-10">
                        <textarea class="form-control ckeditor" id="faq_q_new_0" name="faqs[new_0][question]" placeholder="{{trans($module_name.'.admin.faq_q')}}"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-1">
                    </div>
                    <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.faq_a')}}</label>
                    <div class="col-sm-9">
                        <textarea class="form-control ckeditor" id="faq_a_new_0" name="faqs[new_0][answer]" placeholder="{{trans($module_name.'.admin.faq_a')}}"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2 text-center">
            <input type="hidden" name="faqs[new_0][id]" value="0">
            <button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>
        </div>
    </div>
@endforelse
</div>
<div id="new_faqs">
</div>