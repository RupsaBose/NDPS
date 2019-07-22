<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 
    <meta name="Drug Disposal Monitoring System" content="DDMS is developed by the
    Software Developers of Calcutta High Court and its purpose is to monitor all
    the seizures and its disposals.">
    <meta name="Software Developers" content="Anabil Bhattacharya, Rupsa Bose">
    <meta name="Guide" content="Shri Kallol Chattopadhyay, Shri Abhranil Neogi">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>DDMS</title>    
    
    @include('layouts.css_links')

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
        
            <!-- Logo -->
            @if(Auth::check() && (Auth::user()->user_type == 'ps' || Auth::user()->user_type == 'agency'))
                <a href="entry_form" class="logo"> 
            @elseif(Auth::check() && Auth::user()->user_type == 'high_court')
                <a href="dashboard" class="logo">
            @elseif(Auth::check() && Auth::user()->user_type == 'magistrate')
                <a href="magistrate_entry_form" class="logo"> 
            @elseif(Auth::check() && Auth::user()->user_type == 'special_court')
                <a href="dashboard_special_court" class="logo"> 
            @endif  
                <span class="logo-lg"><b>NDPS</b>
            </a>
        </span>
            </a>
            
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{asset('images/FacelessMan.png')}}" class="user-image" alt="User Image">
                                <span class="hidden-xs">
                                        @if(Auth::check())
                                            {{ Auth::user()->user_name }}
                                        @endif
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{asset('images/FacelessMan.png')}}" class="img-circle" alt="User Image">
                                    <p>
                                        @if(Auth::check())
                                            {{ Auth::user()->user_name }}
                                        @endif
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="update_password" class="btn btn-primary btn-flat">Update Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('logout') }}"  class="btn btn-danger btn-flat"  onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">Sign out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                        <li class="treeview">
                            <a href="#"><i class="fa fa-search-minus"></i>
                                <span>Reports/Enquiry</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Auth::check() && Auth::user()->user_type == 'high_court')
                                    <li><a href="composite_search_highcourt">Composite Search</a></li>
                                    <li><a href="disposed_undisposed_tally">Disposed Undisposed Tally</a></li>
                                @elseif(Auth::check() && (Auth::user()->user_type == 'ps' || Auth::user()->user_type == 'agency'))
                                    <li><a href="composite_search_stakeholder">Composite Search</a></li>
                                @elseif(Auth::check() && Auth::user()->user_type == 'magistrate')
                                    <li><a href="composite_search_magistrate">Composite Search</a></li>
                                @elseif(Auth::check() && Auth::user()->user_type == 'special_court')
                                    <li><a href="composite_search_specialcourt">Composite Search</a></li>
                                @endif
                            </ul>
                        </li>
                        
                    @if(Auth::check() && Auth::user()->user_type == 'high_court')
                        <li class="header"></li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-edit"></i>
                                <span>Master Maintenance</span>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                {{-- <li><a href="district_view"><i class="fa fa-circle-o text-red"></i> <span>District Master Maintenance</span></a></li> --}}
                                <li><a href="court_view"><i class="fa fa-circle-o text-yellow"></i> <span>Court Master Maintenance</span></a></li>
                                <li><a href="stakeholder_view"><i class="fa fa-circle-o text-violate"></i> <span>Stakeholder Master Maintenance</span></a></li>
                                <li><a href="narcotic_view"><i class="fa fa-circle-o text-green"></i> <span>Narcotic Master Maintenance</span></a></li>
                                <li><a href="unit_view"><i class="fa fa-circle-o text-black"></i> <span>Unit Master Maintenance</span></a></li>
                                <li><a href="ps_view"><i class="fa fa-circle-o text-blue"></i> <span>PS Master Maintenance</span></a></li>
                                <li><a href="storage_view"><i class="fa fa-circle-o text-brown"></i> <span>Storage Master Maintenance</span></a></li>
                             </ul>
                        </li>

                        <li class="header"></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-user"></i>
                                <span>User Maintainance</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="create_new_user">User Creation</a></li>
                                {{-- <li><a href="#">Remove User</a></li> --}}
                            </ul>
                        </li>
                    @endif

                    {{-- <li class="header"></li>
                    <li>
                        <a href="faq"><i class="fa fa-book" aria-hidden="true"></i><span><strong>Frequently Asked Question</strong></span></a></li>
                    </li>                     --}}
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- this will be used to logout after being inactive for 5 minutes -->
        <div style="display:none;">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="submit" id="submit">
            </form>
        </div>
             

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content">
                @yield('content')
            
        
        @include('layouts.js_links')