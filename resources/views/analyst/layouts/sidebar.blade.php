<nav class="sidebar">
    <ul class="list-unstyled list-group list-group-flush main-menu">
        <li id="bor" class="list-group-item list-group-item-action">
            <a class="hov title {{in_array($subtitle,['detail','grant']) ? 'active':''}}" href="{{url('/analyst/orders')}}" aria-expanded="false" id="bor">
                <i class="far fa-list-alt pr-1"></i>
                {{trans('analyst.menu_title')}}
            </a>
            <ul id="order_list" class="list list-unstyled">
                <li>
                    <a class="hov subtitle1 {{$subtitle=='detail'? 'active':''}}" href="{{url('/analyst/orders')}}">
                        <span class="text" aria-expanded="false">{{trans('analyst.detail_title')}}</span>
                    </a>
                    <a class="hov subtitle2 {{$subtitle=='grant'? 'active':''}}" href="{{url('/analyst/grants')}}">
                        <span class="text" aria-expanded="false">{{trans('analyst.grant_title')}}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="list-group-item list-group-item-action" id="bor">
            <a class="hov title {{$subtitle=='promocode'? 'active':''}}" href="{{url('/analyst/promocodes')}}" aria-expanded="false">
                <i class="fa fa-comment-dollar pr-1"></i>
                {{trans('analyst.detail_promocode_title')}}
            </a>   
        </li>
    </ul>
</nav>
<div class="sidebar-toggler">
    <button class="navbar-toggler" type="button" id="sidebarToggle" aria-expanded="false">
       <i class="fa fa-clipboard"></i>
    </button>
</div>
