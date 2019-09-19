<div class="col-12 text-center new_solution_btn_container" style="display:none">
    <button class="btn btn-success" id="new_solution_btn"><span class="oi oi-plus"></span></button>
</div>

@if( ($data && !isset($data->plans[0]['introduction'])) || !$data)
    <div id="solutions">
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
    </div>
    <div id="new_solutions"></div>
@else
    @if($data && $data->plans->count()!=0)
        <div id="solutions">
        @foreach( $data->plans as $key => $plan )
            <div  class="form-group row">
                <div class="col-sm-10 ">
                    <div class="form-group row expert_affiliated_product_select_container">
                        <label class="form-control-label col-sm-2" for="expert_affiliated_products{{$plan->id}}">{{trans($module_name.'.admin.expert_affiliated_products')}}</label>
                        <div class="col-sm-8">
                            <select class="form-control chosen-select expert_affiliated_products" id="expert_affiliated_products{{$plan->id}}" name="plans[{{$plan->id}}][expert_affiliated_product_select][]" multiple="multiple">
                            @if($data->affiliated_products && $data->affiliated_products->count()!=0)
                                @foreach($data->affiliated_products as $product)
                                    <option value="{{$product->id}}" {{($data && $data->solutions->where('solution_product_id',$product->id)->where('product_prices_id', $plan->id)->count())? 'selected':''}}>{{$product->name}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                        <div class="col-sm-2 text-danger msg">
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-sm-2" for="plan_intro{{$plan->id}}">{{trans($module_name.'.admin.plan_intro')}}</label>
                        <div class="col-sm-8">
                            <textarea class="form-control solution_intro" id="plan_intro{{$plan->id}}" rows="6" name="plans[{{$plan->id}}][plan_intro]" placeholder="{{trans($module_name.'.admin.plan_intro')}}">{{$plan->introduction}}</textarea>
                        </div>
                        <div class="col-sm-2 text-danger msg">
                                    
                        </div>
                    </div>
                    <div class="form-group row free_courses_container">
                        <label class="form-control-label col-sm-2" for="free_courses{{$plan->id}}">{{trans($module_name.'.admin.free_courses')}}</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="free_courses{{$plan->id}}" name="plans[{{$plan->id}}][free_courses]" placeholder="{{trans($module_name.'.admin.free_courses')}}" value="{{$plan->freecourses}}" min="0">
                        </div>
                        <div class="col-sm-2 text-danger msg">
                                    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label col-sm-2" for="year_price{{$plan->id}}">{{trans($module_name.'.admin.year_price')}}</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="hidden" name="plans[{{$plan->id}}][id]" value="{{$plan->id}}">
                            <input class="form-control" type="hidden" name="plans[{{$plan->id}}][expiration]" value="12">
                            <input class="form-control" type="hidden" name="plans[{{$plan->id}}][active]" value="1" >
                            <input type="text" class="form-control" id="year_price{{$plan->id}}" name="plans[{{$plan->id}}][price]" placeholder="{{trans($module_name.'.admin.year_price')}}" value="{{$plan->price}}">
                        </div>
                        <div class="col-sm-2 text-danger msg">
                                    
                        </div>
                    </div>
                </div>
                <div class="col-2 text-center">
                    <button class="btn btn-danger remove_btn" type="button" value="{{$plan->id}}"><span class="oi oi-trash"></span></button>
                </div>
            </div>
        @endforeach  
        </div>
    @endif
    <div id="new_solutions"></div>
@endif