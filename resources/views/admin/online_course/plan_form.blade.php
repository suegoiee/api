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
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[normal][name]" value="normal">
                            一般費用
                        </td>
                        <td>
                            <input class="form-control" type="text" name="plans[normal][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                            <input class="form-control" type="checkbox" name="plans[normal][active]" value="1" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control" type="hidden" name="plans[early][name]" value="early">
                            早鳥票
                        </td>
                        <td>
                            <input class="form-control" type="text" name="plans[early][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                            <input class="form-control" type="checkbox" name="plans[early][active]" value="1" >
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <input class="form-control" type="hidden" name="plans[group][name]" value="group">
                            團體票
                        </td>
                        <td>
                            <input class="form-control" type="text" name="plans[group][price]" value=""  placeholder="0為免費">
                        </td>
                        <td>
                            <input class="form-control" type="checkbox" name="plans[group][active]" value="1" >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>