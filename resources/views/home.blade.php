@extends('layouts.app')
@section('css_file')
<link rel="stylesheet" href="{{asset('css/home.css')}}">
@endsection
@section('content')
<form>
    <div class="form-row">
        <div class="col-sm-2 pt-2">
            <select class="form-control bg-success" id="method">
                <option value="GET" class="bg-white text-dark" >GET</option>
                <option value="POST" class="bg-white text-dark" >POST</option>
                <option value="PUT" class="bg-white text-dark" >PUT</option>
                <option value="DELETE" class="bg-white text-dark" >DELETE</option>
            </select>
        </div>
        <div class="col-sm pt-2">
            <input type="text" class="form-control" placeholder="/api/user" id="url">
        </div>
        <div class="col-sm-1 text-center pt-2">
            <button class="btn btn-info" id="submit_btn">submit</button>
        </div>
    </div>
    <div id="post_data">
        <h4 class="pt-3">Post Data:</h4>
        <div id="posts">
            <div class="form-row">
                <div class="col-sm-1 pt-2">
                    <select class="form-control input_type">
                        <option value="TEXT">TEXT</option>
                        <option value="FILE">FILE</option>
                    </select>
                </div>
                <div class="col-sm-2 pt-2">
                    <input type="text" class="form-control input_key" placeholder="Key" value="email">
                </div>
                <div class="col-sm-8 pt-2">
                    <input type="text" class="form-control input_value" placeholder="Value" value="">
                </div>
                <div class="col-sm-1 text-center pt-2">
                    <button class="btn btn-danger remove_btn"><span class="oi oi-x"></span></button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-1 pt-2">
                    <select class="form-control input_type">
                        <option value="TEXT">TEXT</option>
                        <option value="FILE">FILE</option>
                    </select>
                </div>
                <div class="col-sm-2 pt-2">
                    <input type="text" class="form-control input_key" placeholder="Key" value="password">
                </div>
                <div class="col-sm-8 pt-2">
                    <input type="text" class="form-control input_value" placeholder="Value" value="">
                </div>
                <div class="col-sm-1 text-center pt-2">
                    <button class="btn btn-danger remove_btn"><span class="oi oi-x"></span></button>
                </div>
            </div>
        </div>
        <div class="form-row text-center pt-2">
            <div class="col-sm-12 text-center">
                <button class="btn btn-info" id="posts_btn"><span class="oi oi-plus"></span></button>
            </div>
        </div>
    </div>
    <h4 class="pt-3">Headers:</h4>
    <div id="headers">
        <div class="form-row">
            <div class="col-sm-3 pt-2">
                <input type="text" class="form-control input_key" placeholder="Key" value="Authorization">
            </div>
            <div class="col-sm-8 pt-2">
                <input type="text" class="form-control input_value" placeholder="Value" value="Bearer ">
            </div>
            <div class="col-sm-1 text-center pt-2">
                <button class="btn btn-danger remove_btn"><span class="oi oi-x"></span></button>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-3 pt-2">
                <input type="text" class="form-control input_key" placeholder="Key" value="Accept">
            </div>
            <div class="col-sm-8 pt-2">
                <input type="text" class="form-control input_value" placeholder="Value" value="application/json" >
            </div>
            <div class="col-sm-1 text-center pt-2">
                <button class="btn btn-danger remove_btn"><span class="oi oi-x"></span></button>
            </div>
        </div>
    </div>
    <div class="form-row text-center pt-2">
        <div class="col-sm-12 text-center">
            <button class="btn btn-info" id="headers_btn"><span class="oi oi-plus"></span></button>
        </div>
    </div>
</form>
<div class="row pt-5">
    <h4 class="pt-3">Response:</h4>
    <textarea class="form-control" id="response_data" rows="10" ></textarea>
</div>
@endsection

@section('javascript')
<script>

    $(function(){
        $('#submit_btn').click(function(event){
            event.preventDefault();
            var headers={};
            $('#headers .form-row').each(function(index,item){
             headers[$(item).find('.input_key').val().trim()]=$(item).find('.input_value').val().trim();
         });
            var formData = {};
            var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
            var processData = 'application/x-www-form-urlencoded';
            var cache = true;
            var method = $('#method').val();
            if($('#method').val().trim()!='GET'){
                method = 'POST';
                formData = new FormData();
                formData.append('_method',$('#method').val());
                processData = false;
                contentType = false;
                cache = false;
                $('#posts .form-row').each(function(index,item){
                    var input_type = $(item).find('.input_type').val().trim();
                    var input_key = $(item).find('.input_key').val().trim();
                    var input_value = $(item).find('.input_value');
                    if(input_type == 'FILE'){
                        formData.append(input_key,input_value[0].files[0]);
                    }else if(input_type == 'TEXT'){
                        formData.append(input_key,input_value.val().trim());
                    }
                });
            }
            $.ajax({
                type: method,
                url: url($('#url').val().trim()),
                headers: headers,
                cache: cache,
                contentType: contentType,
                processData: processData,
                data: formData,
                dataType : 'json',
                complete: function(response) {
                    show_response(response.responseText);
                //$('#response_data').val(JSON.stringify(JSON.parse(response.responseText),null,2));
                //$('#response_data').val(response.responseText);
            }
        });
        });
        $('#headers_btn').click(function(event){
            event.preventDefault();
            var header_input_html='<div class="form-row">'+
            '<div class="col-sm-3 pt-2">'+
            '<input type="text" class="form-control input_key" placeholder="Key" >'+
            '</div>'+
            '<div class="col-sm-8 pt-2">'+
            '<input type="text" class="form-control input_value" placeholder="Value" >'+
            '</div>'+
            '<div class="col-sm-1 text-center pt-2">'+
            '<button class="btn btn-danger remove_btn"><span class="oi oi-x"></span></button>'+
            '</div>'+
            '</div>';
            $('#headers').append(header_input_html);
        });
        $('#headers').on('click','.remove_btn',function(event){
            event.preventDefault();
            $(this).parent().parent().remove();
        });
        $('#posts_btn').click(function(event){
            event.preventDefault();
            var posts_input_html='<div class="form-row">'+
            '<div class="col-sm-1 pt-2">'+
            '<select class="form-control input_type">'+
            '<option value="TEXT">TEXT</option>'+
            '<option value="FILE">FILE</option>'+
            '</select>'+
            '</div>'+
            '<div class="col-sm-2 pt-2">'+
            '<input type="text" class="form-control input_key" placeholder="Key" >'+
            '</div>'+
            '<div class="col-sm-8 pt-2">'+
            '<input type="text" class="form-control input_value" placeholder="Value" >'+
            '</div>'+
            '<div class="col-sm-1 text-center pt-2">'+
            '<button class="btn btn-danger remove_btn"><span class="oi oi-x"></span></button>'+
            '</div>'+
            '</div>';
            $('#posts').append(posts_input_html);
        });
        $('#posts').on('click','.remove_btn',function(event){
            event.preventDefault();
            $(this).parent().parent().remove();
        });
        $('#method').change(function(event){
            if($(this).val().trim()!='GET'){
                $('#post_data').show();
            }else{
                $('#post_data').hide();
            }
        });
        $('#method').change(function(event){
            if($(this).val().trim()!='GET'){
                $('#post_data').show();
            }else{
                $('#post_data').hide();
            }
        });
        $('#method').change();
        function show_response(response){
            var data;
            try{
                data =  JSON.stringify(JSON.parse(response),null,2);
            //data = JSON.stringify(response);
        }catch (e) {
            data = response;
        }

        $('#response_data').val(data);
    }

    $('#posts').on('change','.input_type',function(event){
        var input_value=$(this).parent().parent().find('.input_value');
        var input_box = input_value.parent();
        var input_html;
        if($(this).val().trim()=='FILE'){
            input_html='<input type="file" class="form-control input_value" placeholder="File" >';
        }else{
            input_html='<input type="text" class="form-control input_value" placeholder="Value" >';
        }
        input_box.html(input_html);
    });
});
</script>
@endsection
