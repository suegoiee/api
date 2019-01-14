<nav class="sidebar">
    <ul class="list-unstyled list-group list-group-flush main-menu" >
        <li id="bor" class="list-group-item list-group-item-action">
            <a class="hov title" href="#order_list" aria-expanded="false">
                <i class="far fa-list-alt pr-1"></i>
                {{trans('analyst.menu_title')}}
            </a>
            <ul id="order_list" class="list list-unstyled">
                <li>
                    <a class="hov subtitle1" href="{{url('/analyst/orders')}}">
                        <span class="text" aria-expanded="false">{{trans('analyst.detail_title')}}</span>
                    </a>
                    <a class="hov subtitle2" href="{{url('/analyst/grants')}}">
                        <span class="text" aria-expanded="false">{{trans('analyst.grant_title')}}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="list-group-item list-group-item-action" id="bor">
            <a class="hov title" href="{{url('/analyst/promocodes')}}" aria-expanded="false">
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
