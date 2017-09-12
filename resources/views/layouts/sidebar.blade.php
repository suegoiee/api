<nav class="sidebar">
    <ul class="list-unstyled main-menu">
    <!--
        <li class="active">
            <a href="#submenu" data-toggle="collapse" aria-expanded="false">
                Home
            </a>
            <ul class="collapse list-unstyled" id="submenu">
                <li><a href="#">Home 1</a></li>
                <li><a href="#">Home 2</a></li>
                <li><a href="#">Home 3</a></li>
            </ul>
        </li>-->
        <li>
            <a href="{{url('/admin/products')}}">
                <span class="oi oi-box"></span>
                <span class="text">{{trans('product.menu_title')}}</span>
            </a>
        </li> 
        <li>
            <a href="{{url('/admin/orders')}}">
                <span class="oi oi-clipboard"></span>
                <span class="text">{{trans('order.menu_title')}}</span>
            </a>
        </li>
        <li>
            <a href="{{url('/admin/users')}}">
                <span class="oi oi-people"></span>
                <span class="text">{{trans('user.menu_title')}}</span>
            </a>
        </li>
         <li>
            <a href="{{url('/admin/tags')}}">
                <span class="oi oi-tag"></span>
                <span class="text">{{trans('tag.menu_title')}}</span>
            </a>
        </li>
    </ul>
</nav>

<button class="navbar-toggler" type="button" id="sidebarToggle" aria-expanded="false">
   <span class="oi oi-menu"></span>
</button>
