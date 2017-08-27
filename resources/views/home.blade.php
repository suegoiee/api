@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
@endsection
@section('content')
<div class="container pt-5">
    <form>
        <div class="form-row">
            <div class="col-sm-2 pt-2">
                <select class="form-control" id="method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                </select>
            </div>
            <div class="col-sm pt-2">
                <input type="text" class="form-control" placeholder="/api/user" id="url">
            </div>
            <div class="col-sm-1 text-center pt-2">
                <button class="btn btn-primary" id="submit_btn">submit</button>
            </div>
        </div>
    </form>
    <div class="row pt-5">
        <textarea class="form-control" id="response_data"></textarea>
    </div>
 </div>
@endsection

@section('javascript')
<script>
$(function(){
    $('#submit_btn').click(function(event){
        event.preventDefault();
        $.ajax({
            type: $('#method').val(),
            url: url($('#url').val().trim()),
            dataType : 'json',
            complete: function(response) {
                $('#response_data').val(response.responseText);
            }
        });
    });
    function show_response(response){
        $('#response_data').val(response);
    }
});
</script>
@endsection