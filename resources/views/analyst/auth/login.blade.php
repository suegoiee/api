@extends('analyst.layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/analyst/home.css')}}">
@endsection
@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{url('/analyst/login')}}">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h2 class="text-center">{{trans('analyst.auth.login_title')}}</h2>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group has-danger">
                        <label class="sr-only" for="name">{{trans('analyst.auth.email_label')}}</label>
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" name="email" class="form-control" id="email" placeholder="{{trans('analyst.auth.email_label')}}" required="" autofocus="">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-control-feedback">
                        <span class="text-danger align-middle">
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="sr-only" for="password">{{trans('analyst.auth.password_label')}}</label>
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="password" name="password" class="form-control" id="password" placeholder="{{trans('analyst.auth.password_label')}}" required="">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-control-feedback">
                        <span class="text-danger align-middle">
                            {{$errors->first('email')}}
                        </span>
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            <div class="row" style="padding-top: 1rem">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn col-4">
                        {{trans('analyst.auth.login')}} 
                        <i class="fa fa-sign-in-alt"></i>
                    </button>
                </div>
            </div>
        </form>

@endsection

@section('javascript')

@endsection