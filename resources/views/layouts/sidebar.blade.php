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
        @can('permission', ['AnnouncementController', 'index'])
        <li>
            <a href="{{url('/admin/announcements')}}">
                <span class="oi oi-volume-high"></span>
                <span class="text">{{trans('announcement.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['EdmController', 'index'])
        <li>
            <a href="{{url('/admin/edms')}}">
                <span class="oi oi-flag"></span>
                <span class="text">{{trans('edm.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['ProductController', 'index'])
        <li>
            <a href="{{url('/admin/products')}}">
                <span class="oi oi-box"></span>
                <span class="text">{{trans('product.admin.menu_title')}}</span>
            </a>
        </li> 
        @endcan
        @can('permission', ['OrderController', 'index'])
        <li>
            <a href="{{url('/admin/orders')}}">
                <span class="oi oi-clipboard"></span>
                <span class="text">{{trans('order.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['UserController', 'index'])
        <li>
            <a href="{{url('/admin/users')}}">
                <span class="oi oi-people"></span>
                <span class="text">{{trans('user.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['TagController', 'index'])
        <li>
            <a href="{{url('/admin/tags')}}">
                <span class="oi oi-tag"></span>
                <span class="text">{{trans('tag.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['CompanyController', 'index'])
        <li>
            <a href="{{url('/admin/companies')}}">
                <span class="oi oi-briefcase"></span>
                <span class="text">{{trans('company.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['MessageController', 'index'])
        <li>
            <a href="{{url('/admin/messages')}}">
                <span class="oi oi-envelope-closed"></span>
                <span class="text">{{trans('message.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['ArticleController', 'index'])
        <li>
            <a href="{{url('/admin/articles')}}">
                <span class="oi oi-script"></span>
                <span class="text">{{trans('article.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['PromocodeController', 'index'])
        <li>
            <a href="{{url('/admin/promocodes')}}">
                <span class="oi oi-bookmark"></span>
                <span class="text">{{trans('promocode.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['NotificationMessageController', 'index'])
        <li>
            <a href="{{url('/admin/notificationMessages')}}">
                <span class="oi oi-bullhorn"></span>
                <span class="text">{{trans('notificationMessage.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['AanalystController', 'index'])
        <li>
            <a href="{{url('/admin/analysts')}}">
                <span class="oi oi-audio-spectrum"></span>
                <span class="text">{{trans('analyst.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['ReferrerController', 'index'])
        <li>
            <a href="{{url('/admin/referrers')}}">
                <span class="oi oi-badge"></span>
                <span class="text">{{trans('referrer.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['EventController', 'index'])
        <li>
            <a href="{{url('/admin/events')}}">
                <span class="oi oi-bullhorn"></span>
                <span class="text">{{trans('event.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['OnlineCourseController', 'index'])
        <li>
            <a href="{{url('/admin/online_courses')}}">
                <span class="oi oi-monitor"></span>
                <span class="text">{{trans('online_course.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['PhysicalCourseController', 'index'])
        <li>
            <a href="{{url('/admin/physical_courses')}}">
                <span class="oi oi-book"></span>
                <span class="text">{{trans('physical_course.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['ExpertController', 'index'])
        <li>
            <a href="{{url('/admin/experts')}}">
                <span class="oi oi-thumb-up"></span>
                <span class="text">{{trans('expert.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['ForumCategoeyController', 'index'])
        <li>
            <a href="{{url('/admin/forumCategories')}}">
                <span class="oi oi-list-rich"></span>
                <span class="text">{{trans('forumCategory.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
        @can('permission', ['RoleController', 'index'])
        <li>
            <a href="{{url('/admin/roles')}}">
                <span class="oi oi-key"></span>
                <span class="text">{{trans('role.admin.menu_title')}}</span>
            </a>
        </li>
        @endcan
    </ul>
</nav>
<div class="sidebar-toggler">
    <button class="navbar-toggler" type="button" id="sidebarToggle" aria-expanded="false">
       <span class="oi oi-menu"></span>
    </button>
</div>
