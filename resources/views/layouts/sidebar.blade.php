<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
             <div class="info nav-link">
                Welcome {{ucwords(Auth::user()->name)}} &nbsp; &nbsp;
            </div>
        <li>
        @include('layouts.notification')
        <!-- <div id="notifications"></div> -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-power-off"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
            </form>
        </li>
    </ul>
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
    <img src="{{ url('/') }}/images/logo.png" alt="AdminLTE Logo" class="brand-image  elevation-3"
        style="opacity: .8">
    <span class="brand-text font-weight-light">District 10</span>
    </a>
    <div class="sidebar">
         @if(AdminHelper::checkUserIsChapterAdmin())
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('manage-user') }}" class="nav-link">
                        <i class="fas fa-users"></i>
                        @if(AdminHelper::checkUserIsSuperAdmin())
                            <p>&nbsp;Manage Users</p>
                        @else 
                            <p>&nbsp;Manage Members</p>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manage-event') }}" class="nav-link">
                        <i class="fas fa-hiking"></i>
                        <p>&nbsp;Manage Events</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manage-document') }}" class="nav-link">
                        <i class="fas fa-id-card-alt"></i>
                        <p>&nbsp;Manage Documents</p>
                    </a>
                </li>
                
                @if(AdminHelper::checkUserIsSuperAdmin())
                    <li class="nav-item">
                        <a href="{{ route('manage-document-type') }}" class="nav-link">
                            <i class="fas fa-id-card-alt"></i>
                            <p>&nbsp;Manage Document Types</p>
                        </a>
                    </li>
                    @endif
                <li class="nav-item">
                    <a href="{{ route('manage-contractor-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Manage Contractor Directory</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manage-jatc-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Manage JATC Directory</p>
                    </a>
                </li>                
                <li class="nav-item">
                    <a href="{{ route('manage-chapter-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Manage Chapter Directory</p>
                    </a>
                </li>
                @if(AdminHelper::checkUserIsSuperAdmin())
                <li class="nav-item">
                    <a href="{{ route('manage-ibew-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Manage IBEW Directory</p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('manage-contractor-resources') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Manage Contractor Resources</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manage-notification') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Manage Announcement</p>
                    </a>
                </li>                
                </a>
            </ul>
        </nav>
        @endif
         @if(AdminHelper::checkUserIsOnlyUserAdmin())  
         <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('view-user-events') }}" class="nav-link">
                        <i class="fas fa-hiking"></i>
                        <p>&nbsp;Events</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('view-user-documents') }}" class="nav-link">
                        <i class="fas fa-id-card-alt"></i>
                        <p>&nbsp;Documents</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('view-user-contractor-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Contractor Directory</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('view-user-jatc-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;JATC Directory</p>
                    </a>
                </li>                
                <li class="nav-item">
                    <a href="{{ route('view-user-chapter-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;Chapter Directory</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('view-user-ibew-directories') }}" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        <p>&nbsp;IBEW Directory</p>
                    </a>
                </li>
                </a>
            </ul>
        </nav>
         @endif
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>