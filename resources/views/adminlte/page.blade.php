@extends('adminlte.master')

@section('adminlte_css')
    @stack('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte.partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('adminlte.adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning  @if($currentUser->notification_count == 0) hidden @endif">{{ $currentUser->notification_count }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have {{ $currentUser->notification_count }} notifications</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    {{--<ul class="menu">--}}
                                        {{--<li>--}}
                                            {{--<a href="#">--}}
                                                {{--<i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">--}}
                                                {{--<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the--}}
                                                {{--page and may cause design problems--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">--}}
                                                {{--<i class="fa fa-users text-red"></i> 5 new members joined--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">--}}
                                                {{--<i class="fa fa-shopping-cart text-green"></i> 25 sales made--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">--}}
                                                {{--<i class="fa fa-user text-red"></i> You changed your username--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <li>
                            @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte.log_out') }}
                                </a>
                            @else
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte.log_out') }}
                                </a>
                                <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                    @if(config('adminlte.logout_method'))
                                        {{ method_field(config('adminlte.logout_method')) }}
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </li>
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ $currentUser->avatar ? $currentUser->avatar : asset('images/default-avatar.png') }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ $currentUser->nickname }}</p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>


                <!-- search form -->
                <div method="get" class="sidebar-form" id="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="搜索..." id="search-input">
                        <span class="input-group-btn">
                            <div class="btn btn-flat"><i class="fa fa-search"></i></div>
                        </span>
                    </div>
                </div>
                <!-- /.search form -->

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte.partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div id="container" class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script>
        $(function () {
            $('#search-input').on('keyup', function () {
                var term = $('#search-input').val().trim();

                if (term.length === 0) {
                    $('.sidebar-menu li').each(function () {
                        $(this).show(0);
                        $(this).removeClass('active');
                        if ($(this).data('lte.pushmenu.active')) {
                            $(this).addClass('active');
                        }
                    });
                    return;
                }

                $('.sidebar-menu li').each(function () {
                    if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
                        $(this).hide(0);
                        $(this).removeClass('pushmenu-search-found', false);

                        if ($(this).is('.treeview')) {
                            $(this).removeClass('active');
                        }
                    } else {
                        $(this).show(0);
                        $(this).addClass('pushmenu-search-found');

                        if ($(this).is('.treeview')) {
                            $(this).addClass('active');
                        }

                        var parent = $(this).parents('li').first();
                        if (parent.is('.treeview')) {
                            parent.show(0);
                        }
                    }

                    if ($(this).is('.header')) {
                        $(this).show();
                    }
                });

                $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
                    $(this).find('.pushmenu-search-found').show(0);
                });
            });
        });
    </script>
    @stack('js')
@stop
