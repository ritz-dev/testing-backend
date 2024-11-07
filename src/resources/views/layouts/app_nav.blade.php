{{-- Header Bar --}}
<div class="header header-one">

    <div class="header-left active" style="width: 220px !important;">
        <a href="{{ url('/dashboard') }}" class="logo logo-normal d-none d-lg-block" style="max-width: 50px;">
            <img src="{{ asset('assets/images/barbericon.png') }}" alt="">
        </a>
        <p class="fw-bold ms-5 ms-lg-0">NOT Barber Shop</p>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">

        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">

            </div>
        </li>

        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-img">
                        <img src="{{ asset('assets/images/barbericon.png') }}" alt="" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role_id">{{ Auth::user()->email  ?? ''}}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ Auth::user()->name }}</h6>
                            <h5>{{ Auth::user()->email ?? ''}}</h5>
                        </div>
                    </div>
                    <hr class="m-0">

                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('logout') }}">
                        <i class="fas fa-power-off" style="color: #ff0000; margin-right: 5px;"></i>
                        Logout
                    </a>
                </div>
            </div>
        </li>
    </ul>


    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
        </div>
    </div>

</div>

{{-- Desktop view top navigation bar --}}
<div class="sidebar new-header sidebar-one">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul class="nav">
            @if (auth()->user()->role_id==1 || auth()->user()->role_id==2) 
                <li>
                    <i class="mdi mdi-apple" data-bs-toggle="tooltip" title="" data-bs-original-title="mdi-apple"
                        aria-label="mdi-apple"></i>
                    <a href="{{ url('/dashboard') }}">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span></span>
                    </a>
                </li>
            @endif
            <li class="submenu">
                <a href="#">
                    <i class="fas fa-calendar me-2"></i>
                    <span>Appointments</span><span class="menu-arrow"></span>
                </a>
                <ul>
                    <li><a href="{{ url('/calendar') }}">Appointments Calendar</a></li>
                    <li><a href="{{ url('/appointments/create') }}">Create Appointment</a>
                    <li><a href="{{ url('/appointments') }}">Appointment List</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#">
                    <i class="fas fa-walking me-2"></i>
                    <span> Walk in</span> <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li><a href="{{ url('/walk_in/create') }}">Add walk in customer</a></li>
                    <li><a href="{{ url('/walk_in') }}">Walk in list</a></li>

            </li>
        </ul>
        </li>
        @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
            <li class="submenu">
                <a href="#">
                    <i class="fas fa-users me-2"></i>
                    <span>Customers</span><span class="menu-arrow"></span>
                </a>
                <ul>
                    <li><a href="{{ url('customers/create') }}">New Customer</a>
                    </li>
                    <li><a href="{{ url('customers') }}">Customers List</a></li>
                </ul>
            </li>
        @endif
        <li class="submenu">
            <a href="#">
                <i class="fas fa-user-alt me-2"></i>
                <span>Barbers</span><span class="menu-arrow"></span>
            </a>
            <ul>
                @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                    <li><a href="{{ url('barbers/create') }}">Add Barber </a></li>
                @endif
                <li><a href="{{ url('barbers') }}">Barbers Details</a></li>
                @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                    <li><a href="{{ url('absence') }}">Barbers Absence Details</a></li>
                @endif
            </ul>
        </li>
        

        <li class="submenu">
            <a href="#">
                <i class="fas fa-file-alt me-2"></i>
                <span>Reports</span><span class="menu-arrow"></span>
            </a>
            <ul>
                <li><a href="{{ url('/salary_report') }}">Salary
                        Report</a></li>
                <li><a href="{{ url('/performance_report') }}">Barber Performance
                        Report</a></li>
                @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                    <li><a href="{{ url('/services_report') }}">Services Report</a></li>
                    <li><a href="{{ url('/products_report') }}">Products Report</a></li>
                    <li><a href="{{ url('/expenses_report') }}">Expenses Report</a></li>
                @endif
            </ul>
        </li>
        <li class="submenu">
            <a href="#">
                <i class="fas fa-sliders-h me-2"></i>
                <span>Management</span><span class="menu-arrow"></span>
            </a>
            <ul>
                    <li><a href="{{ url('/services') }}">Services</a></li>
                    @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                        <li><a href="{{ url('/products') }}">Products</a></li>
                        <li><a href="{{ url('/expense') }}">Expense</a></li>
                        @endif
                        <li><a href="{{ url('/user-list') }}">Users</a></li>
            </ul>
        </li>
        @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
        <li class="submenu">
            <a href="#">
                <i class="fas fa-image me-2"></i>
                <span>Views</span><span class="menu-arrow"></span>
            </a>
            <ul>
                @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
		    <li><a href="{{ route('home_views.index') }}">Home</a></li>
                    <li><a href="{{ url('/gallery') }}">Views</a></li>
                    <li><a href="{{ url('/shop_addresses') }}">Address View</a></li>
                    <li><a href="{{ url('/close-days') }}">CloseDay View</a></li>
                @endif
                @if (auth()->user()->role_id==1)
                    <li><a href="{{ url('/audit_logs') }}">Audit Logs</a></li>
                @endif
            </ul>
        </li>
        @endif
        </ul>
    </div>
</div>

{{-- Mobile view side bar --}}
<div class="sidebar sidebar-one hide-sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ url('/dashboard') }}">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span></span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-calendar me-2"></i>
                        <span>Appointments</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ url('/calendar') }}">Appointments Calendar</a></li>
                        <li><a href="{{ url('/appointments/create') }}">Create Appointment</a></li>
                        <li><a href="{{ url('/appointments') }}">Appointment List</a>
                        </li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-walking me-2"></i>
                        <span> Walk in</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ url('/walk_in/create') }}">Add walk in customer</a></li>
                        <li><a href="{{ url('/walk_in') }}">Walk in list</a></li>
                    </ul>

                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-users me-2"></i>
                        <span>Customers</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ url('/customers/create') }}">Add Customer
                            </a>
                        </li>
                        <li><a href="{{ url('/customers') }}">Customers
                                List</a>
                        </li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-user-alt me-2"></i>
                        <span>Barbers</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ url('barbers/create') }}">Add Barber </a></li>
                        <li><a href="{{ url('barbers') }}">Barbers Details</a></li>
                        <li><a href="{{ url('absence') }}">Barbers Absence Details</a></li>

                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-file-alt me-2"></i>
                        <span>Reports</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ url('/salary_report') }}">Salary
                                Report</a></li>
                        <li><a href="{{ url('/performance_report') }}">Barber Performance
                                Report</a></li>
                        <li><a href="{{ url('/services_report') }}">Services
                                Report</a></li>
                        <li><a href="{{ url('/products_report') }}">Products
                                Report</a></li>
                                <li><a href="{{ url('/expenses_report') }}">Expenses Report</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fas fa-sliders-h me-2"></i>
                        <span>Management</span><span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ url('/services') }}">Services
                            </a></li>
                        <li><a href="{{ url('/products') }}">Products</a>
                        </li>
                        <li><a href="{{ url('/user-list') }}">Users</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
