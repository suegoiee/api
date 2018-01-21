<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_supplier_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="suppliers">
@php 
    $suppliers = $data? $data->suppliers : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="40%">{{trans($module_name.'.admin.supplier_name')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.supplier_value')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_supplier_list">
                
            </tbody>
            <tbody id="supplier_list">
                @foreach($suppliers as $key => $supplier)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="suppliers[{{$key}}][id]" value="{{$supplier->id}}">
                        <td class="name_col">
                            <div class="data">{{$supplier->name}}</div>
                            <input type="hidden" name="suppliers[{{$key}}][name]" value="{{$supplier->name}}">
                            <div class="editor"></div>
                        </td>
                        <td class="value_col">
                            <div class="data">{!!$supplier->value!!}</div>
                            <input type="hidden" name="suppliers[{{$key}}][value]" value="{{$supplier->value}}">
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