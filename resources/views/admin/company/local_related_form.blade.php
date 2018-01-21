<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_local_related_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="local_relateds">
@php 
    $local_relateds = $data? $data->local_relateds : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="40%">{{trans($module_name.'.admin.local_related_name')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.local_related_value')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_local_related_list">
                
            </tbody>
            <tbody id="local_related_list">
                @foreach($local_relateds as $key => $local_related)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="local_relateds[{{$key}}][id]" value="{{$local_related->id}}">
                        <td class="name_col">
                            <div class="data">{{$local_related->name}}</div>
                            <input type="hidden" name="local_relateds[{{$key}}][name]" value="{{$local_related->name}}">
                            <div class="editor"></div>
                        </td>
                        <td class="value_col">
                            <div class="data">{!!$local_related->value!!}</div>
                            <input type="hidden" name="local_relateds[{{$key}}][value]" value="{{$local_related->value}}">
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