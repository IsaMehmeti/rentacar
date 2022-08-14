<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">

        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">


                <ul class="nav nav-main">
                    <li class="{{ Request::is('/') ? 'nav-active' : '' }}">
                        <a class="nav-link" href="{{url('/')}}">
                            <i class="fas fa-columns"></i>
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    </li>
                    <li class="nav-parent {{ Request::is('registers*') ? 'nav-expanded nav-active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fa fa-bookmark"></i>
                            <span> {{__('Regjistri')}}</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ Request::is('registers') ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{route('registers.index')}}">
                                    {{__('Regjistri')}}
                                </a>
                            </li>
                            <li class="{{ Request::is('registers/create') ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{route('registers.create')}}">
                                    {{__('Shto ne Regjister')}}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ Request::is('cars*') ? 'nav-expanded nav-active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fa fa-car"></i>
                            <span> {{__('Veturat')}}</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ Request::is('cars') ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{route('cars.index')}}">
                                    {{__('Veturat')}}
                                </a>
                            </li>
                            <li class="{{ Request::is('cars/create') ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{route('cars.create')}}">
                                    {{__('Shto Veturen')}}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent {{ Request::is('clients*') ? 'nav-expanded nav-active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fa fa-users"></i>
                            <span> {{__('Klientët')}}</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ Request::is('clients') ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{route('clients.index')}}">
                                    {{__('Klientët')}}
                                </a>
                            </li>
                            <li class="{{ Request::is('clients/create') ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{route('clients.create')}}">
                                    {{__('Shto Klientin')}}
                                </a>
                            </li>
                        </ul>
                    </li>
{{--                    <li class="nav-parent {{ Request::is('file*') ? 'nav-expanded nav-active' : '' }}">--}}
{{--                        <a class="nav-link" href="#">--}}
{{--                            <i class="fab fa-google-drive"></i>--}}
{{--                            <span>{{__('messages.File Storage')}}</span>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-children">--}}
{{--                            <li class="{{ Request::is('file') ? 'nav-active' : '' }}">--}}
{{--                                <a class="nav-link" href="{{url('/file')}}">--}}
{{--                                    {{__('messages.File Storage')}}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="{{ Request::is('file/create') ? 'nav-active' : '' }}">--}}
{{--                                <a class="nav-link" href="{{url('/file/create')}}">--}}
{{--                                    {{__('messages.Create File')}}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                    <li class="{{ Request::is('user/create') ? 'nav-active' : '' }}">
                        <a class="nav-link" href="{{route('user.create')}}">
                            <i class="fas fa-users"></i>
                            <span>{{__('Shto Perdorues')}}</span>
                        </a>
                    </li>
{{--                    <li class="{{ Request::is('calendar') ? 'nav-active' : '' }}">--}}
{{--                        <a class="nav-link" href="{{url('/calendar')}}">--}}
{{--                            <i class="fas fa-calendar-alt"></i>--}}
{{--                            <span>{{__('messages.Calendar')}}</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
            </nav>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
        </div>

    </div>

</aside>
<!-- end: sidebar -->
