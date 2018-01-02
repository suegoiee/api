<div  class="form-group row">
    <div class="col-12 text-center">
        <button class="btn btn-success" id="new_event_btn"><span class="oi oi-plus"></span></button>
    </div>
</div>
<div id="events">
@php 
    $events = $data? $data->events->sortByDesc('year') : [] ;
@endphp
<div class="form-group row justify-content-center">
    <div class="col-10">
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th class="text-center" width="100px">{{trans($module_name.'.admin.event_year')}}</th>
                    <th class="text-center">{{trans($module_name.'.admin.event_content')}}</th>
                    <th class="text-center" width="100px"></th>
                </tr>
            </thead>
            <tbody id="new_event_list">
                
            </tbody>
            <tbody id="event_list">
                @foreach($events as $key => $event)
                    <tr data-index="{{$key}}">
                        <input type="hidden" name="events[{{$key}}][id]" value="{{$event->id}}">
                        <td class="year_col">
                            <div class="data">{{$event->year}}</div>
                            <input type="hidden" name="events[{{$key}}][year]" value="{{$event->year}}">
                            <div class="editor"></div>
                        </td>
                        <td class="content_col">
                            <div class="data">{!!$event->content!!}</div>
                            <input type="hidden" name="events[{{$key}}][content]" value="{{$event->content}}">
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