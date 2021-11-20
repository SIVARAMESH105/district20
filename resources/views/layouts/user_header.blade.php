<header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="{{ route('user-index') }}"><img src="{{ asset('images/logo.png') }}"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <ul>
                                    <li class="{{ Route::current()->getName()=='user-agreement'?'active':'' }}"><a class="nav-item nav-link" href="{{ route('user-agreement') }}">Agreements & Maps</a></li>
                                    <li class="{{ Route::current()->getName()=='user-directory'?'active':'' }}"><a class="nav-item nav-link" href="{{ route('user-directory') }}">Directories</a></li>
                                    <li class="{{ Route::current()->getName()=='user-calendar'?'active':'' }}"><a class="nav-item nav-link" href="{{ route('user-calendar') }}">Calendar</a></li>
                                    <li class="{{ Route::current()->getName()=='user-resource'?'active':'' }}"><a class="nav-item nav-link" href="{{ route('user-resource') }}">Resources</a></li>
                                    <li class="{{ Route::current()->getName()=='user-contact'?'active':'' }}"><a class="nav-item nav-link" href="{{ route('user-contact') }}">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                @if (Auth::check())
                <div class="col-lg-4 text-center text-lg-right welcome-info">
                    <div class="user-name-info">
                        <p>Welcome, <span class="user-name">{{ Auth::user()->name }}</span></p>
                    </div>
                    <div class="header-notification">
                        <ul>
                            <li><a href="{{ route('user-announcement') }}" class="user-notification"></a></li>
                            <li class="btn-group"><a href="javascript:;" class="user-setting dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="user-list">
                                        <li><a class="dropdown-item change-password-link {{ Route::current()->getName()=='user-change-password'?'active':'' }}" href="{{ route('user-change-password') }}">Change Password</a></li>
                                        <li><a class="dropdown-item profile-link {{ Route::current()->getName()=='user-settings'?'active':'' }}" href="{{ route('user-settings') }}">Settings</a></li>
                                        <li><a class="dropdown-item logout-link" href="{{ route('user-logout') }}">Logout</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                 @endif   
            </div>
        </div>
    </header>
