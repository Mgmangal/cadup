<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="{{asset('uploads/'.is_setting('app_favicon'))}}">
    <link href="{{asset('assets/css/vendor.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"
        rel="stylesheet" />
    {{ $css }}
</head>

<body>
    <style>
    .datepicker {
        padding: .4375rem .75rem;
        border-radius: 6px;
        /* margin-bottom: 15px; */
    }

    .form-group {
        margin-bottom: 15px;
    }

    .loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        visibility: hidden;
    }

    .loader h3 {
        position: absolute !important;
        top: 60% !important;
        left: 46% !important;
        text-shadow: 3px 3px 5px rgb(42 47 42);
    }
    </style>

    <div id="app" class="app app-sidebar-minified=">
        <div id="header" class="app-header">
            <div class="mobile-toggler">
                <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
            <div class="brand">
                <div class="desktop-toggler">
                    <button type="button" class="menu-toggler" data-toggle="sidebar-minify">
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                </div>
                <a href="{{route('app.dashboard')}}" class="brand-logo">
                    <img src="{{asset('uploads/'.is_setting('app_logo'))}}" alt="" height="20" />
                </a>
            </div>
            <div class="menu">
                <form class="menu-search" method="POST" name="header_search_form">

                </form>
                <div class="menu-item dropdown d-flex align-items-center">
                    <label>Timezone</label>
                    <select onchange="changeTimezone(this.value);" class="form-control ms-2" style="width: 100px;">
                        <option {{ session('timezone') == 'Asia/Kolkata' ? 'selected' : '' }} value="Asia/Kolkata">IST</option>
                        <option {{ session('timezone') == 'UTC' ? 'selected' : '' }} value="UTC">UTC</option>
                    </select>
                </div>
                <div class="menu-item dropdown">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" data-display="static" class="menu-link">
                        <div class="menu-img online">
                            <img src="{{asset('assets/img/user/user.jpg')}}" alt="" class="ms-100 mh-100 rounded-circle" />
                        </div>
                        <div class="menu-text"><span>{{ Auth::user()->name }}</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right me-lg-3">
                        <a class="dropdown-item d-flex align-items-center" href="{{route('user.profile')}}">Edit Profile
                            <i class="fa fa-user-circle fa-fw ms-auto text-dark text-opacity-50"></i></a>
                        <a class="dropdown-item d-flex align-items-center" href="{{route('user.password')}}">Password <i
                                class="fa fa-wrench fa-fw ms-auto text-dark text-opacity-50"></i></a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);"
                                onclick="event.preventDefault();this.closest('form').submit();">Log Out <i
                                    class="fa fa-toggle-off fa-fw ms-auto text-dark text-opacity-50"></i></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar" class="app-sidebar">
            <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
                <div class="menu">
                    <div class="menu-header">Navigation</div>
                    <div class="menu-item {{ request()->routeIs('app.dashboard') ? 'active' : ''}}">
                        <a href="{{route('app.dashboard')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-laptop"></i></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </div>
                    <!-- Settings menu -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.settings.contract.type')||request()->routeIs('app.settings.passenger')||request()->routeIs('app.settings.expenditure')||request()->routeIs('app.settings.postFlightDoc')|| request()->routeIs('app.settings.leaveType')||request()->routeIs('app.settings.expensesType') || request()->routeIs('app.states') || request()->routeIs('app.cities') || request()->routeIs('app.settings.aircraftType')||request()->routeIs('app.settings.sfarate')||request()->routeIs('app.settings.flyingtype')||request()->routeIs('app.settings.pilotrole')||request()->routeIs('app.settings.sectors')||request()->routeIs('app.settings.roles')||request()->routeIs('app.settings.departments')||request()->routeIs('app.settings.designations')||request()->routeIs('app.settings.jobfunctions')||request()->routeIs('app.settings.sections')||request()->routeIs('app.settings.certificates')||request()->routeIs('app.settings') ? 'active' : ''}}">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Settings</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            @can('Passenger View')
                            <div class="menu-item {{ request()->routeIs('app.settings.passenger') ? 'active' : ''}}">
                                <a href="{{route('app.settings.passenger')}}" class="menu-link">
                                    <span class="menu-text">Passenger</span>
                                </a>
                            </div>
                            @endcan
                            @can('SFA Rate View')
                            <div class="menu-item {{ request()->routeIs('app.settings.sfarate') ? 'active' : ''}}">
                                <a href="{{route('app.settings.sfarate')}}" class="menu-link">
                                    <span class="menu-text">SFA Rate</span>
                                </a>
                            </div>
                            @endcan
                            @can('Sector View')
                            <div class="menu-item {{ request()->routeIs('app.settings.sectors') ? 'active' : ''}}">
                                <a href="{{route('app.settings.sectors')}}" class="menu-link">
                                    <span class="menu-text">Sectors</span>
                                </a>
                            </div>
                            @endcan
                            @can('Role View')
                            <div class="menu-item {{ request()->routeIs('app.settings.roles') ? 'active' : ''}}">
                                <a href="{{route('app.settings.roles')}}" class="menu-link">
                                    <span class="menu-text">Roles</span>
                                </a>
                            </div>
                            @endcan
                            @can('Designation View')
                            <div class="menu-item {{ request()->routeIs('app.settings.designations') ? 'active' : ''}}">
                                <a href="{{route('app.settings.designations')}}" class="menu-link">
                                    <span class="menu-text">Designation</span>
                                </a>
                            </div>
                            @endcan
                            @can('Department View')
                            <div class="menu-item {{ request()->routeIs('app.settings.departments') ? 'active' : ''}}">
                                <a href="{{route('app.settings.departments')}}" class="menu-link">
                                    <span class="menu-text">Department</span>
                                </a>
                            </div>
                            @endcan
                            @can('Section View')
                            <div class="menu-item {{ request()->routeIs('app.settings.sections') ? 'active' : ''}}">
                                <a href="{{route('app.settings.sections')}}" class="menu-link">
                                    <span class="menu-text">Sections</span>
                                </a>
                            </div>
                            @endcan
                            @can('Job Function View')
                            <div class="menu-item {{ request()->routeIs('app.settings.jobfunctions') ? 'active' : ''}}">
                                <a href="{{route('app.settings.jobfunctions')}}" class="menu-link">
                                    <span class="menu-text">Job Function</span>
                                </a>
                            </div>
                            @endcan
                            @can('Certificate View')
                            <div class="menu-item {{ request()->routeIs('app.settings.certificates') ? 'active' : ''}}">
                                <a href="{{route('app.settings.certificates')}}" class="menu-link">
                                    <span class="menu-text">Certificates</span>
                                </a>
                            </div>
                            @endcan
                            @can('Setting View')
                            <div class="menu-item {{ request()->routeIs('app.settings') ? 'active' : ''}}">
                                <a href="{{route('app.settings')}}" class="menu-link">
                                    <span class="menu-text">Settings</span>
                                </a>
                            </div>
                            @endcan
                            @can('Pilot Role View')
                            <div class="menu-item {{ request()->routeIs('app.settings.pilotrole') ? 'active' : ''}}">
                                <a href="{{route('app.settings.pilotrole')}}" class="menu-link">
                                    <span class="menu-text">Pilot Role</span>
                                </a>
                            </div>
                            @endcan
                            @can('Flying Type View')
                            <div class="menu-item {{ request()->routeIs('app.settings.flyingtype') ? 'active' : ''}}">
                                <a href="{{route('app.settings.flyingtype')}}" class="menu-link">
                                    <span class="menu-text">Flying Type</span>
                                </a>
                            </div>
                            @endcan
                            @can('Aircraft Type View')
                            <div class="menu-item {{ request()->routeIs('app.settings.aircraftType') ? 'active' : ''}}">
                                <a href="{{route('app.settings.aircraftType')}}" class="menu-link">
                                    <span class="menu-text">Aircraft Type</span>
                                </a>
                            </div>
                            @endcan
                            @can('State View')
                            <div class="menu-item {{ request()->routeIs('app.states') ? 'active' : '' }}">
                                <a href="{{ route('app.states') }}" class="menu-link">
                                    <span class="menu-text">States</span>
                                </a>
                            </div>
                            @endcan
                            @can('Citie View')
                            <div class="menu-item {{ request()->routeIs('app.cities') ? 'active' : '' }}">
                                <a href="{{ route('app.cities') }}" class="menu-link">
                                    <span class="menu-text">Cities</span>
                                </a>
                            </div>
                            @endcan
                            @can('Expense Type View')
                            <div
                                class="menu-item {{ request()->routeIs('app.settings.expensesType') ? 'active' : '' }}">
                                <a href="{{ route('app.settings.expensesType') }}" class="menu-link">
                                    <span class="menu-text">Expense Type</span>
                                </a>
                            </div>
                            @endcan
                            @can('Leave Type View')
                            <div class="menu-item {{ request()->routeIs('app.settings.leaveType') ? 'active' : '' }}">
                                <a href="{{ route('app.settings.leaveType') }}" class="menu-link">
                                    <span class="menu-text">Leave Type</span>
                                </a>
                            </div>
                            @endcan
                            @can('Post Flight Doc View')
                            <div
                                class="menu-item {{ request()->routeIs('app.settings.postFlightDoc') ? 'active' : '' }}">
                                <a href="{{ route('app.settings.postFlightDoc') }}" class="menu-link">
                                    <span class="menu-text">Post Flight Doc</span>
                                </a>
                            </div>
                            @endcan
                            @can('Expenditure View')
                            <div class="menu-item {{ request()->routeIs('app.settings.expenditure') ? 'active' : '' }}">
                                <a href="{{ route('app.settings.expenditure') }}" class="menu-link">
                                    <span class="menu-text">Expenditure</span>
                                </a>
                            </div>
                            @endcan
                            <div
                                class="menu-item {{ request()->routeIs('app.settings.contract.type') ? 'active' : '' }}">
                                <a href="{{ route('app.settings.contract.type') }}" class="menu-link">
                                    <span class="menu-text">Contract Type</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Employees -->
                    <div class="menu-item {{ request()->routeIs('app.users') ? 'active' : ''}}">
                        <a href="{{route('app.users')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-users"></i></span>
                            <span class="menu-text">Employees</span>
                        </a>
                    </div>
                    <!-- AirCrafts -->
                    <div class="menu-item {{ request()->routeIs('app.air-crafts') ? 'active' : ''}}">
                        <a href="{{route('app.air-crafts')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-plane"></i></span>
                            <span class="menu-text">Aircraft</span>
                        </a>
                    </div>
                    <div class="menu-item {{ request()->routeIs('app.file') ? 'active' : ''}}">
                        <a href="{{route('app.file')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-folder"></i></span>
                            <span class="menu-text">Manage Files</span>
                        </a>
                    </div>
                    <div class="menu-item {{ request()->routeIs('app.my.leave') ? 'active' : ''}}">
                        <a href="{{route('app.my.leave')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-folder"></i></span>
                            <span class="menu-text">My Leave</span>
                        </a>
                    </div>
                    <div class="menu-item {{ request()->routeIs('app.stamp_ticket') ? 'active' : ''}}">
                        <a href="{{route('app.stamp_ticket')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-tag"></i></span>
                            <span class="menu-text">Stamp Tickets</span>
                        </a>
                    </div>
                    <!-- Manage Oprations -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.flying-details.lkoheVilkLko')||request()->routeIs('app.flying-details.receiveFlightDoc')||request()->routeIs('app.flying-details.process')|| request()->routeIs('app.pilot')|| request()->routeIs('app.external.flying-details')||request()->routeIs('app.pilot.monitoring')||request()->routeIs('app.flying-details.create')||request()->routeIs('app.pilot.leave')||request()->routeIs('app.pilot.availability')||request()->routeIs('app.air-crafts.availability')||request()->routeIs('app.fdtl.monitoring')||request()->routeIs('app.flying-details') ? 'active' : ''}}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Manage Operations</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.flying-details.create') ? 'active' : ''}}">
                                <a href="{{route('app.flying-details.create')}}" class="menu-link">
                                    <span class="menu-text">Post Flying Data Entry</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.flying-details') ? 'active' : ''}}">
                                <a href="{{route('app.flying-details')}}" class="menu-link">
                                    <span class="menu-text">Flying Logs</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.flying-details.lkoheVilkLko') ? 'active' : ''}}">
                                <a href="{{route('app.flying-details.lkoheVilkLko')}}" class="menu-link">
                                    <span class="menu-text">Lko Flying Logs</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.flying-details.process') ? 'active' : ''}}">
                                <a href="{{route('app.flying-details.process')}}" class="menu-link">
                                    <span class="menu-text">Process Flying Logs</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.external.flying-details') ? 'active' : ''}}">
                                <a href="{{route('app.external.flying-details')}}" class="menu-link">
                                    <span class="menu-text">External Flying Logs</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.pilot') ? 'active' : ''}}">
                                <a href="{{route('app.pilot')}}" class="menu-link">
                                    <span class="menu-text">Manage Pilots</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.pilot.leave') ? 'active' : ''}}">
                                <a href="{{route('app.pilot.leave')}}" class="menu-link">
                                    <span class="menu-text">Manage Leave</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.pilot.availability') ? 'active' : ''}}">
                                <a href="{{route('app.pilot.availability')}}" class="menu-link">
                                    <span class="menu-text">Crew Availability</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.air-crafts.availability') ? 'active' : ''}}">
                                <a href="{{route('app.air-crafts.availability')}}" class="menu-link">
                                    <span class="menu-text">Aircraft Availability</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.fdtl.monitoring') ? 'active' : ''}}">
                                <a href="{{route('app.fdtl.monitoring')}}" class="menu-link">
                                    <span class="menu-text">FDTL Monitoring</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.pilot.monitoring') ? 'active' : ''}}">
                                <a href="{{route('app.pilot.monitoring')}}" class="menu-link">
                                    <span class="menu-text">Crew Monitoring</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.flying-details.receiveFlightDoc') ? 'active' : ''}}">
                                <a href="{{route('app.flying-details.receiveFlightDoc')}}" class="menu-link">
                                    <span class="menu-text">Receive Flight Doc </span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Flight Trip Planning</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Flight Dispatch</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Flight Tracking</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">AOR List</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Fuel Barrel Monitoring</span>
                                </a>
                            </div>

                        </div>
                    </div>
                    <!-- Manage LTM -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.ltm')||request()->routeIs('app.ltm.renuew')||request()->routeIs('app.ltm.history')||request()->routeIs('app.ltm.log')||request()->routeIs('app.ltm.monitoring')? 'active' : ''}}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Manage LTM</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.ltm.') ? 'active' : ''}}">
                                <a href="{{route('app.ltm')}}" class="menu-link">
                                    <span class="menu-text">LTM Status</span>
                                </a>
                            </div>
                            <!--<div class="menu-item {{ request()->routeIs('app.ltm.renuew') ? 'active' : ''}}">-->
                            <!--    <a href="{{route('app.pilot')}}" class="menu-link">-->
                            <!--        <span class="menu-text">Renew LTM</span>-->
                            <!--    </a>-->
                            <!--</div>-->
                            <div class="menu-item {{ request()->routeIs('app.ltm.history') ? 'active' : ''}}">
                                <a href="{{route('app.ltm.history')}}" class="menu-link">
                                    <span class="menu-text">LTM Renew History</span>
                                </a>
                            </div>
                            <!--<div class="menu-item {{ request()->routeIs('app.ltm.log') ? 'active' : ''}}">-->
                            <!--    <a href="{{route('app.pilot')}}" class="menu-link">-->
                            <!--        <span class="menu-text">Pilot LTM Log</span>-->
                            <!--    </a>-->
                            <!--</div>-->
                            <div class="menu-item {{ request()->routeIs('app.ltm.monitoring') ? 'active' : ''}}">
                                <a href="{{route('app.ltm.monitoring')}}" class="menu-link">
                                    <span class="menu-text">LTM Monitoring</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Manage CBR -->
                    <div class="menu-item has-sub {{ request()->routeIs(['app.cvr','app.fdr']) ? 'active' : '' }}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Manage CVR & FDR</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.cvr') ? 'active' : ''}}">
                                <a href="{{ route('app.cvr') }}" class="menu-link">
                                    <span class="menu-text">CVR</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.fdr') ? 'active' : ''}}">
                                <a href="{{ route('app.fdr') }}" class="menu-link">
                                    <span class="menu-text">FDR</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">CVR/FDR Monitoring</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Manage Airstrips -->
                    <div class="menu-item has-sub ">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Manage Airstrips</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Airstrips List</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Maintenance Task</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Feedbacks</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Private Contracts</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Manage Flight Safety -->
                    <div class="menu-item has-sub ">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Manage Flight Safety</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Bird/Animal Strike</span>
                                </a>
                            </div>

                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Flight Safety Docs</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Hazard Management</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Accidents/Incident</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Reports -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs(['app.reports.vipRecency','app.reports.pilotFlyingCurrency','app.reports.pilotGroundTraining','app.external.flying-details.statistics', 'app.pilot.flyingHourMonthly', 'app.sfa', 'app.fdtl', 'app.fdtl.voilations', 'app.flying-details.statistics']) ? 'active' : '' }}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-cog"></i>
                            </span>
                            <span class="menu-text">Reports</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div
                                class="menu-item  {{ request()->routeIs('app.external.flying-details.statistics') ? 'active' : '' }}">
                                <a href="{{ route('app.external.flying-details.statistics') }}" class="menu-link">
                                    <span class="menu-text">External Flying</span>
                                </a>
                            </div>
                            <div
                                class="menu-item  {{ request()->routeIs('app.pilot.flyingHourMonthly') ? 'active' : '' }}">
                                <a href="{{ route('app.pilot.flyingHourMonthly') }}" class="menu-link">
                                    <span class="menu-text">Pilot Flying Hours</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.reports.pilotGroundTraining') ? 'active' : '' }}">
                                <a href="{{ route('app.reports.pilotGroundTraining') }}" class="menu-link">
                                    <span class="menu-text">Pilot Ground Training</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.reports.vipRecency') ? 'active' : '' }}">
                                <a href="{{ route('app.reports.vipRecency') }}" class="menu-link">
                                    <span class="menu-text">VIP Recency</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">VIP Flying List</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.flying-details.statistics') ? 'active' : ''}}">
                                <a href="{{route('app.flying-details.statistics')}}" class="menu-link">
                                    <span class="menu-text">Flight Statistics</span>
                                </a>
                            </div>
                            <div class="menu-item ">
                                <a href="javascript:void(0);" class="menu-link">
                                    <span class="menu-text">Pilot Log Book</span>
                                </a>
                            </div>
                            <div
                                class="menu-item  {{ request()->routeIs('app.reports.pilotFlyingCurrency') ? 'active' : '' }}">
                                <a href="{{ route('app.reports.pilotFlyingCurrency') }}" class="menu-link">
                                    <span class="menu-text">Pilot Flying Currency</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.sfa') ? 'active' : ''}}">
                                <a href="{{route('app.sfa')}}" class="menu-link">
                                    <span class="menu-text">SFA Report</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.fdtl') ? 'active' : ''}}">
                                <a href="{{route('app.fdtl')}}" class="menu-link">
                                    <span class="menu-text">FDTL Report</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.fdtl.voilations') ? 'active' : ''}}">
                                <a href="{{route('app.fdtl.voilations')}}" class="menu-link">
                                    <span class="menu-text">Violations Summary</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ request()->routeIs('app.fdtl.voilations.report') ? 'active' : ''}}">
                                <a href="{{route('app.fdtl.voilations.report')}}" class="menu-link">
                                    <span class="menu-text">Violations Report</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Manage Flying -->
                    <!-- <div class="menu-item {{ request()->routeIs('app.flying-details') ? 'active' : ''}}">
                        <a href="{{route('app.flying-details')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-tasks"></i></span>
                            <span class="menu-text">Flying Details</span>
                        </a>
                    </div> -->
                    <!-- None Flying -->
                    <div class="menu-item {{ request()->routeIs('app.load.trim') ? 'active' : ''}}">
                        <a href="{{route('app.load.trim')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-book"></i></span>
                            <span class="menu-text">Load & Trim</span>
                        </a>
                    </div>
                    <div class="menu-item {{ request()->routeIs('app.none-flying-details') ? 'active' : ''}}">
                        <a href="{{route('app.none-flying-details')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-book"></i></span>
                            <span class="menu-text">Non-Flying Details</span>
                        </a>
                    </div>
                    <!-- FDTL -->
                    <!-- <div class="menu-item {{ request()->routeIs('app.fdtl') ? 'active' : ''}}">
                        <a href="{{route('app.fdtl')}}" class="menu-link">
                            <span class="menu-icon"><i class="fa fa-file-text"></i></span>
                            <span class="menu-text">FDTL</span>
                        </a>
                    </div> -->
                    <!-- Alcohol Detection Test -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.adt.staff')||request()->routeIs('app.adt.report') ? 'active' : ''}}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-user-md"></i>
                            </span>
                            <span class="menu-text">Alcohol Detection Test</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.adt.staff') ? 'active' : ''}}">
                                <a href="{{route('app.adt.staff')}}" class="menu-link">
                                    <span class="menu-text">Employees</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.adt.report') ? 'active' : ''}}">
                                <a href="{{route('app.adt.report')}}" class="menu-link">
                                    <span class="menu-text">Report</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- receive dispatch -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.receive.leave')||request()->routeIs('app.bill')||request()->routeIs('app.receive')||request()->routeIs('app.dispatch') ? 'active' : ''}}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-user-md"></i>
                            </span>
                            <span class="menu-text">Receipt & Dispatch</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.receive') ? 'active' : ''}}">
                                <a href="{{route('app.receive')}}" class="menu-link">
                                    <span class="menu-text">Receipt</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.bill') ? 'active' : ''}}">
                                <a href="{{route('app.bill')}}" class="menu-link">
                                    <span class="menu-text">Bill</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.receive.leave') ? 'active' : ''}}">
                                <a href="{{route('app.receive.leave')}}" class="menu-link">
                                    <span class="menu-text">Leave</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.dispatch') ? 'active' : ''}}">
                                <a href="{{route('app.dispatch')}}" class="menu-link">
                                    <span class="menu-text">Dispatch</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Manage Library -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.library.car')||request()->routeIs('app.library.fsdms')||request()->routeIs('app.library.generic') ? 'active' : ''}}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-user-md"></i>
                            </span>
                            <span class="menu-text">Manage Library</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.library.car') ? 'active' : ''}}">
                                <a href="#" class="menu-link">
                                    <span class="menu-text">CAR Library</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.library.fsdms') ? 'active' : ''}}">
                                <a href="#" class="menu-link">
                                    <span class="menu-text">FSDMS Library</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.library.generic') ? 'active' : ''}}">
                                <a href="#" class="menu-link">
                                    <span class="menu-text">Generic Library</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Manage Contract / Pass -->
                    <div
                        class="menu-item has-sub {{ request()->routeIs('app.adt.staff')||request()->routeIs('app.adt.report') ? 'active' : ''}}">
                        <a href="#" class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-user-md"></i>
                            </span>
                            <span class="menu-text">Manage Contract / Pass</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div class="menu-item {{ request()->routeIs('app.contract') ? 'active' : ''}}">
                                <a href="{{route('app.contract')}}" class="menu-link">
                                    <span class="menu-text">Monitor Contract</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->routeIs('app.adt.report') ? 'active' : ''}}">
                                <a href="{{route('app.adt.report')}}" class="menu-link">
                                    <span class="menu-text">Monitor AEP</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="menu-divider"></div>
                </div>
            </div>
            <button class="app-sidebar-mobile-backdrop" data-dismiss="sidebar-mobile"></button>
        </div>
        <div id="content" class="app-content card">
            {{$breadcrumb}}
            <hr>
            {{ $slot }}
            <div id="loaders" class="loader">
                <img class="img-fluid" src="{{ asset('assets/img/orange-loader.gif') }}" alt="loader"><br>
                <h3 class="text-white">Please wait...</h3>
            </div>
        </div>
        <a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
        <div class="theme-panel">
            <a href="javascript:void(0);" data-click="theme-panel-expand" class="theme-collapse-btn"><i
                    class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <ul class="theme-list clearfix">
                    <li><a href="javascript:void(0);" class="bg-red" data-theme="theme-red" data-click="theme-selector"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body"
                            data-bs-title="Red" data-original-title="" title="">&nbsp;</a></li>
                    <li><a href="javascript:void(0);" class="bg-pink" data-theme="theme-pink"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Pink" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-orange" data-theme="theme-orange"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Orange" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-yellow" data-theme="theme-yellow"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Yellow" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-lime" data-theme="theme-lime"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Lime" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-green" data-theme="theme-green"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Green" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-teal" data-theme="theme-teal"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Teal" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-cyan" data-theme="theme-cyan"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Aqua" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li class="active"><a href="javascript:void(0);" class="bg-blue" data-theme=""
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Default" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-purple" data-theme="theme-purple"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Purple" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-indigo" data-theme="theme-indigo"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Indigo" data-original-title="" title="">&nbsp;</a>
                    </li>
                    <li><a href="javascript:void(0);" class="bg-gray-600" data-theme="theme-gray-600"
                            data-click="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-container="body" data-bs-title="Gray" data-original-title="" title="">&nbsp;</a>
                    </li>
                </ul>
                <hr class="mb-0" />
                <div class="row mt-10px pt-3px">
                    <div class="col-9 control-label text-dark fw-bold">
                        <div>Dark Mode <span class="badge bg-primary ms-1 position-relative py-4px px-6px"
                                style="top: -1px">NEW</span></div>
                        <div class="lh-14 fs-13px">
                            <small class="text-dark opacity-50">
                                Adjust the appearance to reduce glare and give your eyes a break.
                            </small>
                        </div>
                    </div>
                    <div class="col-3 d-flex">
                        <div class="form-check form-switch ms-auto mb-0 mt-2px">
                            <input type="checkbox" class="form-check-input" name="app-theme-dark-mode"
                                id="appThemeDarkMode" value="1" />
                            <label class="form-check-label" for="appThemeDarkMode">&nbsp;</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{asset('assets/js/vendor.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/app.min.js')}}" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>

    {{ $js }}
    <script>
    $(document).ready(function() {
        $('form input, form textarea, form select, form file').on('input', function() {
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').hide();
            }
        });

        $('.datepicker').on('change', function() {
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').hide();
            }
        });
    });

    function clearError(form) {
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
    }

    function showLoader() {
        $('#loaders').css('visibility', 'visible');
    }

    function hideLoader() {
        $('#loaders').css('visibility', 'hidden');
    }
    </script>
    <script type="text/javascript">
    function success(message) {
        swal({
            title: "Success",
            text: message,
            icon: "success",
            button: "OK",
        });
    }

    function info(message) {
        swal({
            title: "Info",
            text: message,
            icon: "info",
            button: "OK",
        });
    }

    function error(message) {
        swal({
            title: "Error",
            text: message,
            icon: "error",
            button: "OK",
        });
    }

    function warning(message) {
        swal({
            title: "Warning",
            text: message,
            icon: "warning",
            button: "OK",
        });
    }

    function preloader() {
        swal({
            title: "Loading...",
            text: "",
            imageUrl: "{{asset('assets/images/preloader.gif')}}",
        });
    }

    function deleted(url) {
        swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this item?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            swal("Success!", data.message, "success");
                        } else {
                            swal("Error!", data.message, "error");
                        }
                        dataList();
                    }
                });
            } else {}
        });
    }
    </script>
    <script>
    function get_city(e, city_id) {
        var state_id = $(e).val();
        $.ajax({
            url: '{{route("home.get_city")}}',
            method: 'post',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                'state_id': state_id
            },
            success: function(data) {
                $('#' + city_id).html(data);
                // $('select').niceSelect('update');
            }
        });

    }
    function changeTimezone(timezone) {
        $.ajax({
            url: '{{route("home.change_timezone")}}',
            method: 'post',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                'timezone': timezone
            },
            success: function(data) {
                location.reload();
            }
        });
    }
    </script>
</body>

</html>