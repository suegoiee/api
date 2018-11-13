<div  class="form-group row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8 ">
        <table class="table" id="price_table">
            <thead>
                <tr>
                    <th>{{trans($module_name.'.admin.expiration')}}({{trans($module_name.'.admin.month')}})</th>
                    <th>{{trans($module_name.'.admin.price')}}({{trans($module_name.'.admin.unit')}})</th>
                    <th>{{trans($module_name.'.admin.active')}}</th>
                </tr>
            </thead>
            <tbody id="plan_list">
                @if($data && $data->plans->count()!=0)
                @foreach( $data->plans as $key => $plan )
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[{{$key}}][id]" value="{{$plan->id}}">
                            <input class="form-control" type="hidden" name="plans[{{$key}}][expiration]" value="{{$plan->expiration}}">
                            {{trans($module_name.'.admin.expiration_'.$plan->expiration)}}
                        </td>
                        <td>
                           <input class="form-control" type="text" name="plans[{{$key}}][price]" value="{{$plan->price}}"  placeholder="0為免費">
                        </td>
                        <td>
                           <input class="form-control" type="checkbox" name="plans[{{$key}}][active]" value="1" {{$plan->active==1?'checked':''}}>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[new_0][id]" value="0">
                            <input class="form-control" type="hidden" name="plans[new_0][expiration]" value="0">
                            {{trans($module_name.'.admin.expiration_0')}}
                        </td>
                        <td>
                           <input class="form-control" type="text" name="plans[new_0][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                           <input class="form-control" type="checkbox" name="plans[new_0][active]" value="1" >
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[new_1][id]" value="0">
                            <input class="form-control" type="hidden" name="plans[new_1][expiration]" value="1">
                            {{trans($module_name.'.admin.expiration_1')}}
                        </td>
                        <td>
                           <input class="form-control" type="text" name="plans[new_1][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                        <input class="form-control" type="checkbox" name="plans[new_1][active]" value="1" >
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[new_2][id]" value="0">
                            <input class="form-control" type="hidden" name="plans[new_2][expiration]" value="6">
                            {{trans($module_name.'.admin.expiration_6')}}
                        </td>
                        <td>
                           <input class="form-control" type="text" name="plans[new_2][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                        <input class="form-control" type="checkbox" name="plans[new_2][active]" value="1" >
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[new_3][id]" value="0">
                            <input class="form-control" type="hidden" name="plans[new_3][expiration]" value="12">
                            {{trans($module_name.'.admin.expiration_12')}}
                        </td>
                        <td>
                           <input class="form-control" type="text" name="plans[new_3][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                        <input class="form-control" type="checkbox" name="plans[new_3][active]" value="1" >
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
