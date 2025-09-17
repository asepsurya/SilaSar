<!DOCTYPE html>
<html x-data="main" class="" :class="[$store.app.mode]">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="description" content="Premium Tailwind CSS Admin & Dashboard Template" />
    <meta name="author" content="Webonzer" />

    <!-- Site Tiltle -->
    <title>{{ config('app.name') }} |  @yield('title')</title>

    <!-- Site favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />

    <!-- Custom Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    {{-- select 2 --}}
     <!-- jQuery (Select2 depends on jQuery) -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
     <link rel="stylesheet" href="{{ asset('assets/css/customselect2.css') }}">
     {{-- datepicker --}}
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>

    {{-- CSS --}}
    @yield('css')
    <style>
        @media (max-width: 768px){
           .p-7{
            padding: 9px;
        }  
        }
       /* body *:not(.fl-wrapper) {
            z-index: auto !important;
        } */
        .fl-wrapper {
            z-index: 2147483647 !important; /* Nilai maksimum z-index */
        }
        [x-cloak] { display: none !important; }
         @media (max-width: 768px) {

            #container {
                margin-bottom: 50px;
            }
            .right-sidebar{
                z-index: 9999;
            }
     
 
    </style>
       @if(auth()->user()->role =="gold")
       <style>
            #sidebar{
                display: none;
                visibility: hidden;
            }
            @media (min-width: 1024px) {
                .main-container .main-content {
                    margin-left: 0;
                }
            }
       </style>
       @endif
</head>

<body x-data="main" class="antialiased relative font-inter bg-white dark:bg-black text-black dark:text-white text-sm font-normal overflow-x-hidden vertical" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.rightsidebar ? 'right-sidebar' : '', $store.app.menu, $store.app.layout]">
    <!-- Start Menu Sidebar Olverlay -->
    <div x-cloak class="fixed inset-0 bg-[black]/60 z-40 lg:hidden" :class="{'hidden' : !$store.app.sidebar}" @click="$store.app.toggleSidebar()"></div>
    <!-- End Menu Sidebar Olverlay -->

    <!-- Start Right Sidebar Olverlay -->
    <div x-cloak class="fixed inset-0 bg-[black]/60 z-50 2xl:hidden" :class="{'hidden' : !$store.app.rightsidebar}" @click="$store.app.rightSidebar()"></div>
    <!-- End Right Sidebar Olverlay -->


    <!-- Start Main Content -->
    <div class="main-container navbar-sticky flex" :class="[$store.app.navbar]">
        <!-- Start Sidebar -->
          @if(auth()->user()->role !="gold")
            @include('layout.partial.sidebar')
         @endif
        <!-- End sidebar -->

        <!-- Start Content Area -->
        <div class="main-content flex-1">
            <!-- Start Topbar -->
             @if(auth()->user()->role !="gold")
             @include('layout.partial.topbar')
                
            @else
                    @include('layout.partial.topbarUser')
            @endif
            <!-- End Topbar -->

            <!-- Start Content -->
            <div class=" overflow-y-auto overflow-x-hidden">
                 <div class="p-7 min-h-[calc(100vh-145px)]" id="container" >
                    @yield('container')
                 </div>
                
                <!-- Start Footer -->
                <div class="hidden sm:block">
                <footer class=" p-7 bg-white dark:bg-black flex flex-wrap items-center justify-center sm:justify-between gap-3">
                    <p class="text-xs text-black/40 dark:text-white/40">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                    <ul class="flex items-center text-black/40 dark:text-white/40 text-xs gap-5">
                        <li><img src="{{ asset('assets/BI_Logo.png') }}" alt="" width="120"></li>
                        <li> 
                             <img src="{{ asset('assets/app_logo.png') }}" alt="" srcset="" width="130" class="block dark:hidden">
                                <img src="{{ asset('assets/SILASAR-LOGO-white.png') }}" alt="" srcset="" width="130" class="hidden dark:block">
                            </li>
                    </ul>
                </footer>
                </div>
                <!-- End Footer -->
            </div>
            <!-- End Content -->
        </div>
        <!-- End Content Area -->

        <!-- Start Right Sidebar -->
        @include('layout.partial.rightsidebar')
        <!-- End Right Sidebar -->
    </div>
    
    <!-- All javascirpt -->
    <!-- Alpine js -->
    <script src="{{ asset('assets/js/alpine-collaspe.min.js') }}"></script>
    <script src="{{ asset('assets/js/alpine-persist.min.js') }}"></script>
    <script src="{{ asset('assets/js/alpine-ui.min.js') }}" ></script>
    <script src="{{ asset('assets/js/alpine.min.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Custom js -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @yield('js')
</body>
</html>