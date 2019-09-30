<div id="solutions">
    <div  class="form-group row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8 ">
            <table class="table" id="price_table">
                <thead>
                    <tr>
                        <th>優惠名稱</th>
                        <th>價格(元)</th>
                        <th>啟用方案</th>
                    </tr>
                </thead>
                <tbody id="plan_list">
                    @if( $data->plan->isEmpty() )
                    <tr>
                        <td> 
                            <input class="form-control" type="text" name="plans[0][name]" value="方案一">
                            <input class="form-control" type="hidden" name="plans[0][id]" value="new">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="plans[0][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                            <input class="form-control" type="checkbox" name="plans[0][active]" value="1">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control" type="text" name="plans[1][name]" value="方案二">
                            <input class="form-control" type="hidden" name="plans[1][id]" value="new">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="plans[1][price]" value="" placeholder="0為免費">
                        </td>
                        <td>
                            <input class="form-control" type="checkbox" name="plans[1][active]" value="1">
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <input class="form-control" type="text" name="plans[2][name]" value="方案三">
                            <input class="form-control" type="hidden" name="plans[2][id]" value="new">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="plans[2][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                            <input class="form-control" type="checkbox" name="plans[2][active]" value="1">
                        </td>
                    </tr>
                    @else
                        @foreach($data->plan as $plan)
                            <tr>
                                <td> 
                                    <input class="form-control" type="text" name="plans[{{$plan->id}}][name]" value="{{$plan->name}}">
                                    <input class="form-control" type="hidden" name="plans[{{$plan->id}}][id]" value="{{$plan->id}}">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="plans[{{$plan->id}}][price]" value="{{$plan->price}}"  placeholder="0為免費">
                                </td>
                                <td>
                                    <input class="form-control" type="checkbox" name="plans[{{$plan->id}}][active]" value="1" {{$plan->active == '1' ? 'checked' : ''}}>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>