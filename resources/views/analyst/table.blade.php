<div id="table-toolbar">
    <form id="tool_form" action="{{url('/analyst/'.str_plural($module_name))}}" method="GET">
    @if(@in_array('options',$tools))
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="table-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="oi oi-cog"></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="table-options">
                <a class="dropdown-item" href="#" id="delete_action"><span class="oi oi-trash"></span> {{trans('table.delete_label')}}</a>
            </div>
        
        </div>
    @endif
    @if(@in_array('date_range',$tools))
        <div class="input-group input-daterange" data-provide="datepicker" data-date-language="zh-TW" data-date-format="yyyy-mm-dd">
            <label class="col-2">{{trans('table.daterange_label')}}</label>
            <input type="text" class="form-control" name="start_date" value="{{isset($query_string['start_date'])? $query_string['start_date']:date('Y-m').'-01'}}" >
            <input type="text" class="form-control" name="end_date" value="{{isset($query_string['end_date'])? $query_string['end_date']:date('Y-m-d')}}" >
            <button type="submit" class="btn btn-default">{{trans('table.search_label')}}</button>
        </div>
    @endif
    </form>
</div>
<table id="table" class="table table-bordered table-hover table-sm table-striped" data-toggle="table" data-search="true" data-toolbar="#table-toolbar" data-maintain-selected="true" data-unique-id="id" data-pagination="true" data-page-size="25" data-pagination-pre-text="<span class='oi oi-chevron-left'></span>" data-pagination-next-text="<span class='oi oi-chevron-right'></span>" data-pagination-h-align="left" data-pagination-detail-h-align="right">
    <thead class="thead-inverse text-center">
        <tr>
            @foreach($table_head as $th)
                <th data-align="center" data-field="{{$th}}" {!! in_array($th,$table_formatter)?'data-formatter="'.$th.'Formatter"':'' !!} data-sortable="true"
                >
                {{trans('analyst.admin.'.$th)}}
                </th>
            @endforeach
            @if($table_action)
                <th data-align="center" data-formatter="actionFormatter">{{trans('table.action_label')}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>