<div id="table-toolbar">
    @if(!isset($tools) || (isset($tools['options']) && $tools['options']))
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="table-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="oi oi-cog"></span>
        </button>
        <div class="dropdown-menu" aria-labelledby="table-options">
            <a class="dropdown-item" href="#" id="delete_action"><span class="oi oi-trash"></span> {{trans('table.delete_label')}}</a>
        </div>
    </div>
    @endif
</div>
<table id="table" class="table table-bordered table-hover table-sm table-striped" data-toggle="table" data-search="true" data-toolbar="#table-toolbar" data-maintain-selected="true" data-unique-id="id" data-pagination="true" data-page-size="100" data-pagination-pre-text="<span class='oi oi-chevron-left'></span>" data-pagination-next-text="<span class='oi oi-chevron-right'></span>" data-pagination-h-align="left" data-pagination-detail-h-align="right" data-pagination-v-align="both">
    <thead class="thead-inverse text-center">
        <tr>
            @if(!isset($table_action) || $table_action)
            <th data-align="center" data-checkbox="true">{{trans('table.action_label')}}</th>
            @endif
            @foreach($table_head as $th)
                <th data-align="center" data-field="{{$th}}" {!! in_array($th,$table_formatter)?'data-formatter="'.$th.'Formatter"':'' !!} data-sortable="true" 
                >
                {{trans($module_name.'.admin.'.$th)}}
                </th>
            @endforeach
            @if(!isset($table_action) || $table_action)
                <th data-align="center" data-formatter="actionFormatter">{{trans('table.action_label')}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>