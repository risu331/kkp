<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Kementerian Kelautan dan Perikanan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('guest_assets/img/logo-kkp.png')}}">

    <!-- plugin css -->
    <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
    <!-- end plugin css -->

    @stack('plugin-styles')

    <!-- common css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <!-- end common css -->
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
    .btn-primary:hover, .fc .fc-button-primary.fc-button-active:hover:not(:disabled), .fc .fc-button-primary:hover:not(:disabled):active, .swal2-popup .swal2-actions button.swal2-confirm:hover, .wizard>.actions a:hover, div.tox .tox-button:hover:not(.tox-button--naked):not(.tox-button--secondary) {
        color: #fff;
        background-color: #45c5ff;
        border-color: #45c5ff;
    }
    .select2-container {
        width: 100% !important;
        padding: 0;
    }
    </style>

    @stack('style')
</head>

<body data-base-url="{{url('/')}}">
    <script src="{{ asset('assets/js/spinner.js') }}"></script>

    <div class="main-wrapper" id="app">
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    <h4>Admin Panel</h4>
                </a>
                <div class="sidebar-toggler not-active">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="sidebar-body">
                <ul class="nav">
                    <li class="nav-item nav-category">Main</li>
                    <li class="nav-item {{ active_class(['dashboard']) }}">
                        <a href="{{ route('dashboard.index') }}" class="nav-link">
                            <i class="link-icon" data-feather="box"></i>
                            <span class="link-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Menu</li>
                    @if(Auth::user()->role != 'user')
                        <li class="nav-item {{ active_class(['dashboard/fishing-data*']) }}">
                            <a href="{{ route('dashboard.fishing-data.index') }}" class="nav-link">
                                <i class="link-icon" data-feather="layers"></i>
                                <span class="link-title">Pendataan Ikan</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item {{ active_class(['dashboard/statistic/*']) }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#statistic" role="button"
                            aria-expanded="{{ is_active_route(['dashboard/statistic/*']) }}" aria-controls="statistic">
                            <i class="link-icon" data-feather="pie-chart"></i>
                            <span class="link-title">Statistik</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(['dashboard/statistic/*']) }}" id="statistic">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.1.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/1']) }}">Komposisi Hasil Tangkap Ikan</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.2.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/2']) }}">Data Jenis Ikan</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.3.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/3']) }}">Status Appendiks</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.4.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/4']) }}">Alat Tangkap</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.5.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/5']) }}">Jenis Kelamin</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.6.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/6']) }}">Harga Ekonomi</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.statistic.7.index') }}"
                                        class="nav-link {{ active_class(['dashboard/statistic/7']) }}">Frekuensi Panjang</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item {{ active_class(['dashboard/map*']) }}">
                        <a href="{{ route('dashboard.map.index') }}" class="nav-link">
                            <i class="link-icon" data-feather="map"></i>
                            <span class="link-title">Peta</span>
                        </a>
                    </li>
                        @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                            <li class="nav-item nav-category">Data Master</li>
                        @endif
                        @if(Auth::user()->role == 'superadmin')
                            <li class="nav-item {{ active_class(['dashboard/branch*']) }}">
                                <a href="{{ route('dashboard.branch.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="map-pin"></i>
                                    <span class="link-title">Wilayah Kerja</span>
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                            <li class="nav-item {{ active_class(['dashboard/user*']) }}">
                                <a href="{{ route('dashboard.user.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="user"></i>
                                    <span class="link-title">Manajemen User</span>
                                </a>
                            </li>
                            <li class="nav-item {{ active_class(['dashboard/type-ship*']) }}">
                                <a href="{{ route('dashboard.type-ship.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="database"></i>
                                    <span class="link-title">Jenis Kapal</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item {{ active_class(['dashboard/ship*']) }}">
                                <a href="{{ route('dashboard.ship.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="database"></i>
                                    <span class="link-title">Kapal</span>
                                </a>
                            </li> -->
                            <li class="nav-item {{ active_class(['dashboard/fishing-gear*']) }}">
                                <a href="{{ route('dashboard.fishing-gear.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="database"></i>
                                    <span class="link-title">Alat Tangkap Ikan</span>
                                </a>
                            </li>
                            <li class="nav-item {{ active_class(['dashboard/type-fish*']) }}">
                                <a href="{{ route('dashboard.type-fish.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="database"></i>
                                    <span class="link-title">Jenis Ikan</span>
                                </a>
                            </li>
                            <li class="nav-item {{ active_class(['dashboard/species-fish*']) }}">
                                <a href="{{ route('dashboard.species-fish.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="database"></i>
                                    <span class="link-title">Spesies Ikan</span>
                                </a>
                            </li>
                            <li class="nav-item {{ active_class(['dashboard/landing-site*']) }}">
                                <a href="{{ route('dashboard.landing-site.index') }}" class="nav-link">
                                    <i class="link-icon" data-feather="database"></i>
                                    <span class="link-title">Lokasi Pendaratan</span>
                                </a>
                            </li>
                        @endif
                </ul>
            </div>
        </nav>
        <div class="page-wrapper">
            <nav class="navbar">
                <a href="#" class="sidebar-toggler">
                    <i data-feather="menu"></i>
                </a>
                <div class="navbar-content">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="wd-30 ht-30 rounded-circle"
                                    src="{{ Auth::user()->image == null ? url('https://via.placeholder.com/30x30') : Auth::user()->image }}"
                                    alt="profile">
                            </a>
                            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                    <div class="mb-3">
                                        <img class="wd-80 ht-80 rounded-circle"
                                            src="{{ Auth::user()->image == null ? url('https://via.placeholder.com/30x30') : Auth::user()->image }}"
                                            alt="">
                                    </div>
                                    <div class="text-center">
                                        <p class="tx-16 fw-bolder">{{ Auth::user()->name }}</p>
                                        <p class="tx-12 text-muted">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <ul class="list-unstyled p-1">
                                    <a href="{{ route('dashboard.profile.edit', Auth::user()->id) }}" class="text-body ms-0">
                                        <li class="dropdown-item py-2">
                                            <i class="me-2 icon-md" data-feather="edit"></i>
                                            <span>Edit Profile</span>
                                        </li>
                                    </a>
                                    <a href="{{ route('logout') }}" class="text-body ms-0">
                                        <li class="dropdown-item py-2">
                                            <i class="me-2 icon-md" data-feather="log-out"></i>
                                            <span>Log Out</span>
                                        </li>
                                    </a>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="page-content">
                @yield('content')
            </div>
            <footer
                class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small" id="footer">
                <p class="text-muted mb-1 mb-md-0">Copyright © 2022 <a href="#" target="_blank">Ricky Subarja - 10191072</a>.</p>
                <p class="text-muted">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm"
                        data-feather="heart"></i></p>
            </footer>
        </div>
    </div>

    <!-- base js -->
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.ckeditor.com/4.20.1/basic/ckeditor.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAovhxjkjJX16jhgBhuFGJTlIdfJZ8uYCY&libraries=geometry"></script>
    <script src="{{ asset('assets/plugins/selectTree/comboTreePlugin.js') }}"></script>
    @if(session('OK'))
        <script>
          Swal.fire("Berhasil!", "{{ session("OK") }}", "success");
        </script>
      @endif
    
      @if(session('ERR'))
        <script>
        Swal.fire("Error!", "{{ session("ERR") }}", "error");
        </script>
      @endif
    <script>
        $('.select-filter-page').select2();

        $('.select-filter-page').on('select2:opening select2:closing', function( event ) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });
    </script>
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>

</html>
