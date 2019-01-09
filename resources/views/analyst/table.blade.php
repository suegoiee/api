<div id="table-toolbar">
    <form id="tool_form" action="{{url('/analyst/'.str_plural($module_name))}}" method="GET">
    @if(@in_array('options',$tools))
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="table-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-cog"></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="table-options">
                <a class="dropdown-item" href="#" id="delete_action">
                    <span class="fa fa-trash"></span> 
                    {{trans('table.delete_label')}}
                </a>
            </div>
        
        </div>
    @endif
    @if(@in_array('date_range',$tools))
        <div class="input-group input-daterange mt-2" data-provide="datepicker" data-date-language="zh-TW" data-date-format="yyyy-mm-dd">
            <label id="date" class="text-center">
                {{trans('table.daterange_label')}}
            </label>
            <input type="text-2 text" style="fas fa-caret-down" id="dates01" class="form-control dates" name="start_date" value="{{isset($query_string['start_date'])? $query_string['start_date']:date('Y-m').'-01'}}">
            <span class="mx-2 line"> - </span>
            <input type="text-2" class="form-control dates" name="end_date" value="{{isset($query_string['end_date'])? $query_string['end_date']:date('Y-m-d')}}" >
            <button type="submit" class="btn ml-3 text-center btn-default">
                {{trans('table.search_label')}}
            </button>
        </div>
    @endif
    </form>
</div>
<table id="table" class="table table-bordered table-hover table-sm table-striped" data-toggle="table" data-search="true" data-toolbar="#table-toolbar" data-maintain-selected="true" data-unique-id="id" data-pagination="true" data-page-size="25" data-pagination-pre-text="<span class='fa fa-angle-double-left'></span>" data-pagination-next-text="<span class='fa fa-angle-double-right'></span>" data-pagination-h-align="left" data-pagination-detail-h-align="right">
    <thead class="text-center thead-type">
        <tr>
            @foreach($table_head as $th)
                <th data-align="center" data-field="{{$th}}" {!! in_array($th,$table_formatter)?'data-formatter="'.$th.'Formatter"':'' !!} data-sortable="true" {!!isset($table_sorter) && in_array($th,$table_sorter)?'data-sorter="'.$th.'Sorter"':'' !!}>
                    <div>
                        {{trans($module_name.'.analyst.'.$th)}}
                    </div>
                </th>
            @endforeach
            @if($table_action)
                <th data-align="center" data-formatter="actionFormatter">
                    <div>
                        {{trans('table.action_label')}}
                    </div>
                </th>
            @endif
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>