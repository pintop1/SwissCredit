@php
use App\Http\Controllers\Globals as Utils;
$me = Utils::getUser();
$notifs = Utils::getNotifications($me);
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
         <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/images/site.webmanifest') }}">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        @yield('head')
        <link href="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/plugins/hopscotch/dist/css/hopscotch.min.css') }}" rel="stylesheet"></link>
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/dripicons/webfont/webfont.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/icons/LineIcons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <style type="text/css">
            /* Absolute Center Spinner */
            .loading {
              position: fixed;
              z-index: 9999;
              height: 2em;
              width: 2em;
              overflow: show;
              margin: auto;
              top: 0;
              left: 0;
              bottom: 0;
              right: 0;
              display: none;
            }

            /* Transparent Overlay */
            .loading:before {
              content: '';
              display: block;
              position: fixed;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
                background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

              background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
            }

            /* :not(:required) hides these rules from IE9 and below */
            .loading:not(:required) {
              /* hide "loading..." text */
              font: 0/0 a;
              color: transparent;
              text-shadow: none;
              background-color: transparent;
              border: 0;
            }

            .loading:not(:required):after {
              content: '';
              display: block;
              font-size: 10px;
              width: 1em;
              height: 1em;
              margin-top: -0.5em;
              -webkit-animation: spinner 150ms infinite linear;
              -moz-animation: spinner 150ms infinite linear;
              -ms-animation: spinner 150ms infinite linear;
              -o-animation: spinner 150ms infinite linear;
              animation: spinner 150ms infinite linear;
              border-radius: 0.5em;
              -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
            }

            /* Animation */

            @-webkit-keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
            @-moz-keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
            @-o-keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
            @keyframes spinner {
              0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
              }
            }
        </style>
    </head>
    <body>
      <div class="loading">Loading&#8230;</div>
        <div class="leftbar-tab-menu">
            <div class="main-icon-menu">
                <a href="/" class="logo logo-metrica d-block text-center">
                    <span>
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
                    </span>
                </a>
                <nav class="nav">
                    <a href="#Dashboard" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Dashboard">
                        <i class="dripicons-device-desktop align-self-center menu-icon icon-dual"></i> 
                    </a>
                    <a href="#Apps" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Apps">
                        <i class="dripicons-view-thumb align-self-center menu-icon icon-dual"></i> 
                    </a>
                    <a href="#Forms" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Forms">
                        <i class="dripicons-document-edit align-self-center menu-icon icon-dual"></i> 
                    </a>
                    <a href="#Data" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Data">
                        <i class="dripicons-to-do align-self-center menu-icon icon-dual"></i> 
                    </a>
                    <a href="#SwissClub" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="SwissClub">
                        <i class="dripicons-user-group align-self-center menu-icon icon-dual"></i> 
                    </a>
                </nav>
                <div class="pro-metrica-end">
                    <a href="/logout" class="help" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Log Out">
                        <i class="dripicons-exit align-self-center menu-icon icon-md icon-dual mb-4"></i> 
                    </a>
                    @if($me->passport != null)
                    <a href="/profile" class="profile">
                        <img src="{{ asset($me->passport) }}" alt="profile-user" class="rounded-circle thumb-sm">
                    </a>
                    @else
                    <a href="/profile" class="profile">
                        <img src="{{ Gravatar::get($me->email) }}" alt="profile-user" class="rounded-circle thumb-sm">
                    </a>
                    @endif
                </div>
            </div>
            <div class="main-menu-inner">
                <div class="topbar-left">
                    <a href="/" class="logo">
                        <span>
                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark"> 
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo-large" class="logo-lg logo-light">
                        </span>
                    </a>
                </div>
                <div class="menu-body slimscroll">
                    <div id="Dashboard" class="main-icon-menu-pane @yield('dashboard')">
                        <div class="title-box">
                            <h6 class="menu-title">Dashboard</h6>
                        </div>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link @yield('dashboardd')" href="/staff">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link @yield('team')" href="/team">Team</a></li>
                            <!--<li class="nav-item"><a class="nav-link @yield('task')" href="/tasks">Task</a></li>-->
                        </ul>
                    </div>
                    <div id="Apps" class="main-icon-menu-pane @yield('apps')">
                        <div class="title-box">
                            <h6 class="menu-title">Apps</h6>
                        </div>
                        <ul class="nav metismenu">
                            <li class="nav-item"><a class="nav-link @yield('fm')" href="/file-manager">File Manager</a></li>
                            <li class="nav-item"><a class="nav-link @yield('fmrb')" href="/file-manager-recycle-bin">Trash Folder</a></li>
                            <li class="nav-item"><a class="nav-link @yield('fmpe')" href="/file-manager-my-access">Permissions</a></li>
                            <li class="nav-item"><a class="nav-link @yield('fmpep')" href="/file-manager-my-access-pending">Pending Permissions</a></li>
                            <li class="nav-item"><a class="nav-link @yield('bvn')" href="/bvn-verification">BVN Verification</a></li>
                        </ul>
                    </div>
                    <div id="Forms" class="main-icon-menu-pane @yield('forms')">
                        <div class="title-box">
                            <h6 class="menu-title">Forms</h6>
                        </div>
                        <ul class="nav metismenu">
                            <!--<li class="nav-item"><a class="nav-link @yield('ac')" href="/customers/add">Add Clients</a></li>-->
                            <li class="nav-item"><a class="nav-link @yield('as')" href="/staff/add">Add Staff</a></li>
                            <!--<li class="nav-item"><a class="nav-link @yield('at')" href="/tasks/add">Add Task</a></li>-->
                            <li class="nav-item"><a class="nav-link @yield('aoffer')" href="/offers/add">Add Offer</a></li>
                            <li class="nav-item"><a class="nav-link @yield('goffer')" href="/offers/generate">Generate Offer</a></li>
                            <li class="nav-item"><a class="nav-link @yield('transfer')" href="/transfers">Instructions</a></li>
                            <li class="nav-item"><a class="nav-link @yield('asf')" href="/referrals/add">Add Referrals</a></li>
                        </ul>
                    </div>
                    <div id="Data" class="main-icon-menu-pane @yield('data')">
                        <div class="title-box">
                            <h6 class="menu-title">Data</h6>
                        </div>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link @yield('customers')" href="/customers">New Loans</a></li>
                            <li class="nav-item"><a class="nav-link @yield('nysc')" href="/nysc">NYSC</a></li>
                            <li class="nav-item"><a class="nav-link @yield('renewals')" href="/customers/renewals">Renewals</a></li>
                            <li class="nav-item"><a class="nav-link @yield('topups')" href="/customers/topups">Top-ups</a></li>
                            <li class="nav-item"><a class="nav-link @yield('customersP')" href="/customers/processed">Processed Loans</a></li>
                            <li class="nav-item"><a class="nav-link @yield('customersD')" href="/customers/declined">Declined Loans</a></li>
                            <!--<li class="nav-item"><a class="nav-link @yield('generated')" href="/generated-forms">Generated Forms</a></li>-->
                            <li class="nav-item"><a class="nav-link @yield('staffs')" href="/staffs">Staffs</a></li>
                            <li class="nav-item"><a class="nav-link @yield('offer')" href="/offers">Offers</a></li>
                            <li class="nav-item"><a class="nav-link @yield('report')" href="/report">Report</a></li>
                            <li class="nav-item"><a class="nav-link @yield('tasks')" href="/myTasks">My Tasks </a></li>
                            <li class="nav-item"><a class="nav-link @yield('referrals')" href="/referrals">My Referrals</a></li>
                        </ul>
                    </div>
                    <div id="SwissClub" class="main-icon-menu-pane @yield('swissclub')">
                        <div class="title-box">
                            <h6 class="menu-title">Swiss Club</h6>
                        </div>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link @yield('scagents')" href="/swissclub/agents">Agents</a></li>
                            <li class="nav-item"><a class="nav-link @yield('scpayments')" href="/swissclub/payments">Payments</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="topbar">
            <nav class="navbar-custom">
                <ul class="list-unstyled topbar-nav float-right mb-0">
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="lni-alarm noti-icon"></i> 
                            @if(count(Utils::getAllNotifications($me)) > 0)
                            <span class="badge badge-danger badge-pill noti-icon-badge">{{ count(Utils::getAllNotifications($me)) }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">
                            <h6 class="dropdown-item-text font-15 m-0 py-3 bg-primary text-white d-flex justify-content-between align-items-center">Notifications 
                                @if(count(Utils::getAllNotifications($me)) > 0)
                                <span class="badge badge-light badge-pill">{{ count(Utils::getAllNotifications($me)) }}</span>
                                @endif
                            </h6>
                            <div class="slimscroll notification-list">
                                @if(count(Utils::getAllNotifications($me)) > 0)
                                    @foreach($notifs as $notif) 
                                    <a href="{{ $notif->link }}" class="dropdown-item py-3">
                                        <small class="float-right text-muted pl-2">{{ Utils::convertTime($notif->created_at) }}</small>
                                        <div class="media">
                                            <div class="avatar-md {{ $notif->bg }}">{!! $notif->icon !!}</div>
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">{{ $notif->title }}</h6>
                                                <small class="text-muted mb-0">{!! $notif->message !!}</small>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                @else
                                    <a href="javascript:void(0);" class="dropdown-item py-3">
                                        <div class="media">
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">No new notification!</h6>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <!--<a href="/notifications" class="dropdown-item text-center text-primary">View all <i class="fi-arrow-right"></i></a>-->
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            @if($me->passport != null)
                            <img src="{{ asset($me->passport) }}" alt="profile-user" class="rounded-circle">
                            @else
                            <img src="{{ Gravatar::get($me->email) }}" alt="profile-user" class="rounded-circle">
                            @endif 
                            <span class="ml-1 nav-user-name hidden-sm">{{ ucwords(explode(' ', $me->name)[0]) }} <i class="lni-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/profile"><i class="dripicons-user text-muted mr-2"></i> Profile</a> 
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout"><i class="dripicons-exit text-muted mr-2"></i> Logout</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" id="startTourBtn" class="nav-link">
                            <i class="lni-travel align-self-center"></i>
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#" class="nav-link" data-toggle="modal" data-animation="fade" data-target=".modal-rightbar">
                            <i class="lni-text-align-right align-self-center"></i>
                        </a>
                    </li>
                </ul>
                <ul class="list-unstyled topbar-nav mb-0">
                    <li><span class="responsive-logo"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm align-self-center" height="34"></span></li>
                    <li><button class="button-menu-mobile nav-link waves-effect waves-light"><i class="dripicons-menu nav-icon"></i></button></li>
                </ul>
            </nav>
        </div>
        <div class="page-wrapper">
            <div class="page-content-tab">
                <div class="container-fluid">
                    @yield('breadcrumb')
                    @if(session()->has('message'))
                        {!! session()->get('message') !!}
                    @endif
                    @yield('content')
                    <div class="return"></div>
                </div>
                <div class="modal modal-rightbar fade" tabindex="-1" role="dialog" aria-labelledby="MetricaRightbar" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="MetricaRightbar">Appearance</h5>
                                <button type="button" class="btn btn-sm btn-primary btn-circle btn-square" data-dismiss="modal" aria-hidden="true"><i class="mdi mdi-close"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer text-center text-sm-left">&copy; {{ date('Y') }} Swiss Credit <span class="text-muted d-none d-sm-inline-block float-right">Designed, Developed & Powered By <a href="https://pintoptechnologies.com" target="_blank">Pintop Technologies Limited</a></span></footer>
            </div>
        </div>
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.min.js') }}"></script>
        <script src="{{ asset('assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/hopscotch/dist/js/hopscotch.js') }}"></script>
        <script src="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
        @yield('foot')
        <script src="{{ asset('assets/js/app.js') }}"></script>
    </body>
</html>