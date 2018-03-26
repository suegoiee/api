<div class="modal fade" id="importModalCenter" tabindex="-1" role="dialog" aria-labelledby="importModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLongTitle">{{trans('import.title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('admin/'.str_plural($module_name).'/import')}}" method="POST" id="importFrom" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label class="form-control-label col-sm-4" for="promocodefile">{{trans($module_name.'.admin.promocodefile')}} <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="promocodefile" name="promocodefile" placeholder="{{trans($module_name.'.admin.promocodefile')}}">
                        </div>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('import.close')}}</button>
                <button type="button" class="btn btn-primary" onclick="event.preventDefault();
                                                         document.getElementById('importFrom').submit();" data-dismiss="modal">{{trans('import.import')}}</button>
            </div>
        </div>
    </div>
</div>