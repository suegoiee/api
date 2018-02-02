<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_product_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="products">
@php 
    $products = $data? $data->products->sortByDesc('year') : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="15%">{{trans($module_name.'.admin.product_year')}}</th>
                    <th class="text-center" width="40%">{{trans($module_name.'.admin.product_name')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.product_value')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_product_list">
                
            </tbody>
            <tbody id="product_list">
                @foreach($products as $key => $product)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="products[{{$key}}][id]" value="{{$product->id}}">
                        <td class="year_col">
                            <div class="data">{{$product->year}}</div>
                            <input type="hidden" name="products[{{$key}}][year]" value="{{$product->year}}">
                            <div class="editor"></div>
                        </td>
                        <td class="name_col">
                            <div class="data">{{$product->name}}</div>
                            <input type="hidden" name="products[{{$key}}][name]" value="{{$product->name}}">
                            <div class="editor"></div>
                        </td>
                        <td class="value_col">
                            <div class="data">{!!$product->value!!}</div>
                            <input type="hidden" name="products[{{$key}}][value]" value="{{$product->value}}">
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