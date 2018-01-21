<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_customer_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="customers">
@php 
    $customers = $data? $data->customers : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="40%">{{trans($module_name.'.admin.customer_name')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.customer_value')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_customer_list">
                
            </tbody>
            <tbody id="customer_list">
                @foreach($customers as $key => $customer)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="customers[{{$key}}][id]" value="{{$customer->id}}">
                        <td class="name_col">
                            <div class="data">{{$customer->name}}</div>
                            <input type="hidden" name="customers[{{$key}}][name]" value="{{$customer->name}}">
                            <div class="editor"></div>
                        </td>
                        <td class="value_col">
                            <div class="data">{!!$customer->value!!}</div>
                            <input type="hidden" name="customers[{{$key}}][value]" value="{{$customer->value}}">
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