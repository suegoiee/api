<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_area_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="areas">
@php 
    $areas = $data? $data->areas : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="40%">{{trans($module_name.'.admin.area_name')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.area_value')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_area_list">
                
            </tbody>
            <tbody id="area_list">
                @foreach($areas as $key => $area)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="areas[{{$key}}][id]" value="{{$area->id}}">
                        <td class="name_col">
                            <div class="data">{{$area->name}}</div>
                            <input type="hidden" name="areas[{{$key}}][name]" value="{{$area->name}}">
                            <div class="editor"></div>
                        </td>
                        <td class="value_col">
                            <div class="data">{!!$area->value!!}</div>
                            <input type="hidden" name="areas[{{$key}}][value]" value="{{$area->value}}">
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