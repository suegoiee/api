<nav class="sidebar">
    <ul class="list-unstyled main-menu">
        <li>
            <a href="#order_list" data-toggle="collapse" aria-expanded="false">
                {{trans('analyst.menu_title')}}
            </a>
            <ul class="collapse list-unstyled" id="order_list">
                <li>
                    <a href="{{url('/analyst/orders')}}">
                        <span class="oi oi-clipboard"></span>
                        <span class="text">{{trans('analyst.detail_title')}}</span>
                    </a>
                </li>
                <li><a href="{{url('/analyst/grants')}}">
                        <span class="oi oi-dollar"></span>
                        <span class="text">{{trans('analyst.grant_title')}}</span>
                    </a></li>
            </ul>
        </li>
        <li>
            <a href="#promocode_list" data-toggle="collapse" aria-expanded="false">
                {{trans('analyst.menu_promocode_title')}}
            </a>
            <ul class="collapse list-unstyled" id="promocode_list">
                <li>
                    <a href="{{url('/analyst/promocodes')}}">
                        <span class="oi oi-bullhorn"></span>
                        <span class="text">{{trans('analyst.detail_promocode_title')}}</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<div class="sidebar-toggler">
    <button class="navbar-toggler" type="button" id="sidebarToggle" aria-expanded="false">
       <span class="oi oi-menu"></span>
    </button>
</div>
