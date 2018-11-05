<div  class="form-group row">
    <div class="col-sm-3 ">
    </div>
    <div class="col-sm-4 ">
        <table class="table" id="price_table">
            <thead>
                <tr>
                    <th>{{trans($module_name.'.admin.price_label')}}</th>
                    <th>{{trans($module_name.'.admin.expiration_label')}}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input class="form-control" type="text" name="prices[0][price]" value="">
                    </td>
                    <td>
                        <input class="form-control" type="text" name="prices[0][expiration]" value="">
                    </td>
                    <td>
                        <button class="btn btn-info" id="price_add"><span class="oi oi-plus"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
