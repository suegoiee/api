<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_foreign_related_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="foreign_relateds">
@php 
    $foreign_relateds = $data? $data->foreign_relateds : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="40%">{{trans($module_name.'.admin.foreign_related_name')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.foreign_related_value')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_foreign_related_list">
                
            </tbody>
            <tbody id="foreign_related_list">
                @foreach($foreign_relateds as $key => $foreign_related)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="foreign_relateds[{{$key}}][id]" value="{{$foreign_related->id}}">
                        <td class="name_col">
                            <div class="data">{{$foreign_related->name}}</div>
                            <input type="hidden" name="foreign_relateds[{{$key}}][name]" value="{{$foreign_related->name}}">
                            <div class="editor"></div>
                        </td>
                        <td class="value_col">
                            <div class="data">{!!$foreign_related->value!!}</div>
                            <input type="hidden" name="foreign_relateds[{{$key}}][value]" value="{{$foreign_related->value}}">
                            <div class="editor"></div>
                        </td>
                        <td>
                            <div class="action_btns">
                                <span class="oi oi-pencil edit_btn"></span>
                                <span class="oi oi-trash remove_btn"></span>
                            </div>
                            <div class="confirm_btns hide">
                                <span class="oi oi-check confirm_btn"></span>
                                <span class="oi oi-x cancel_btn"></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>