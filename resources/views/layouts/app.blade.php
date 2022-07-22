<!DOCTYPE html>
<html lang="{{Config::get('app.locale')}}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

    <title>@lang('main.title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    @stack('css')
    
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ticketera.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style3.css') }}" rel="stylesheet">
    
    <script src="{{ asset('assets/js/alpine-3.10.2.js') }}" defer></script>

    <script>
      var currentTheme;
      if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        currentTheme = 'dark';
      } else {
        document.documentElement.classList.remove('dark')
        currentTheme = 'light';
      }
    </script>

  </head>
  
  <body>
    
    @yield('modal')

    <!-- Sombra para el sidebar mobile -->
    <div class="overlay"></div>

    <!-- Fondo para el sidebar desktop -->
    {{-- <div id="background" class="d-none d-sm-inline background"></div> --}}
    
    <!-- Menu de navegacion -->
    <nav class="navbar">
        <div class="d-flex">
            <span class="dismiss fa fa-bars text-white ml-3 mr-1" style="margin-top:1.2rem;"></span>
            <a href="/"><li class="navbar-brand text-white">
              @lang('main.title')</li></a>
        </div>

        <div class="d-flex" style="margin:0; padding:0; top:-1rem;">

          <span class="nav-btn theme-toggle" style="padding:.45rem.75rem;margin-right:1rem;width:34px;height:34px;" id="theme-toggle">
            <svg id="theme-toggle-light-icon" class="themebtn" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
            <svg id="theme-toggle-dark-icon" class="themebtn alt" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
          </span>
  
          @if ( Route::has('login') )
          <div class="row mr-2" style="height:1rem;padding-top:.45rem;">
              @if ( Auth::check() )
            
              <div class="btn-group ml-2">
                  <a class="nav-btn mb-0 mt-0 pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="row p-0">
                      <div class="col-auto d-none d-sm-block nav-usertext">{{ Auth::user()->name }}</div>
                      <img src="{{ Auth::user()->avatar }}" alt="" class="nav-userpic">
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right mr-2 mt-0">
                    <div class="text-center">
                      <img src="{{ Auth::user()->avatar }}" alt="" class="nav-userpic-big">
                      <button id="perfil" class="dropdown-item text-center" type="button">@lang('main.menu.profile')</button>
                      <button id="salir" class="dropdown-item text-center" type="button">@lang('main.menu.logout')</button>
                    </div>
                  </div>
              </div>
  
              @else

              <span class="nav-btn">
                <a href="{{ route('login') }}" class="text-white nav-link">
                <i class="fa fa-sign-in-alt mr-2" aria-hidden="true"></i>@lang('login.login')</a>
              </span>

              @endif
          </div>
          @endif

        </div>

    </nav>
    

    <main>
      <div class="loading" id="maincontent">

        <div class="row p-0 m-0">
          
          <!-- Sidebar desktop -->
          <nav class="sidebar col-auto" {{--  id="navbar" --}}>
            <div class="list-group">
  
              <a href="#main-submenu" hidden id="main-link" data-toggle="collapse" aria-expanded="false" class="expandable">@lang('main.sidebar.menu')</a>
              <a href="#admin-submenu" hidden id="admin-link" data-toggle="collapse" aria-expanded="false" class="expandable">@lang('main.sidebar.admin')</a>

              <div class="sidebar-header @if(Request::route()->getPrefix()==null) expanded @endif" id="main-header">
                <span class="ml-0" style="font-weight:700;">@lang('main.sidebar.menu')</span>
                <i class="fa @if(Request::route()->getPrefix()==null) fa-chevron-up @else fa-chevron-down @endif text-right"></i>
              </div>
  
              <ul class="collapse @if(!Request::route()->getPrefix()=='/admin') show @endif list-unstyled mt-2" id="main-submenu">
  
                <li class="sidebaritem @if(Route::currentRouteName()=='incidents.index' || Route::currentRouteName()=='incidents.edit') active @endif">
                    <a href="{{ route('incidents.index') }}">@lang('main.sidebar.incidents')</a>
                </li>
  
                @can('crear_inc')
                <li class="sidebaritem @if(Route::currentRouteName()=='incidents.create') active @endif">
                    <a href="{{ route('incidents.create') }}">@lang('main.sidebar.new_incident')</a>
                </li>
                @endcan
  
                @can('carga_masiva')
                <li class="sidebaritem @if(Route::currentRouteName()=='cargamasiva') active @endif">
                    <a href="{{ route('cargamasiva') }}">@lang('main.sidebar.mass_creation')</a>
                </li>
                @endcan
  
                @can('tablero_control')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'dashboard')) active @endif">
                    <a href="{{ route('dashboard.index') }}">@lang('main.sidebar.dashboard')</a>
                </li>
                @endcan
  
                @can('informes')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'reports')) active @endif">
                    <a href="{{ route('reports.index') }}">@lang('main.sidebar.reports')</a>
                </li>
                @endcan
              
              </ul>
  
              @can ('admin_panel')
              <div class="sidebar-header @if(Request::route()->getPrefix()=='/admin') expanded @endif" id="admin-header">
                <span class="ml-0" style="font-weight:700;">@lang('main.sidebar.admin')</span>
                <i class="fa @if(Request::route()->getPrefix()=='/admin') fa-chevron-up @else fa-chevron-down @endif text-right"></i>
              </div>
              
  
              <ul class="collapse @if(Request::route()->getPrefix()=='/admin') show @endif list-unstyled mt-2" id="admin-submenu">
  
                @can ('admin_usuarios')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'users')) active @endif">
                    <a href="{{ route('users.index') }}">@lang('main.sidebar.users')</a>
                </li>
                @endcan
  
                @can ('admin_usuarios')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'groups')) active @endif">
                    <a href="{{ route('groups.index') }}">@lang('main.sidebar.groups')</a>
                </li>
                @endcan
  
                @can ('admin_clientes')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'clients')) active @endif">
                    <a href="{{ route('clients.index') }}">@lang('main.sidebar.clients')</a>
                </li>
                @endcan
                
                @can ('admin_roles')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'roles')) active @endif">
                  <a href="{{ route('roles.index') }}">@lang('main.sidebar.roles')</a>
                </li>
                @endcan
                
                @can ('admin_areas')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'areas')) active @endif">
                    <a href="{{ route('areas.index') }}">@lang('main.sidebar.areas')</a>
                </li>
                @endcan
                
                @can ('admin_modulos')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'modules')) active @endif">
                    <a href="{{ route('modules.index') }}">@lang('main.sidebar.modules')</a>
                </li>
                @endcan
                
                @can ('admin_tipoincidente')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'problems')) active @endif">
                    <a href="{{ route('problems.index') }}">@lang('main.sidebar.problems')</a>
                </li>
                @endcan
                
                @can ('admin_tiposervicio')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'servicetypes')) active @endif">
                    <a href="{{ route('servicetypes.index') }}">@lang('main.sidebar.service_types')</a>
                </li>
                @endcan
                
                @can ('admin_tipoavance')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'progresstypes')) active @endif">
                    <a href="{{ route('progresstypes.index') }}">@lang('main.sidebar.progress_types')</a>
                </li>
                @endcan
  
                {{-- @if (Auth::user()->rol_id==1)              
                <li class="sidebaritem @if(Route::currentRouteName()=='reglas') active @endif">
                    <a href="{{ route('reglas') }}">@lang('main.sidebar.rules')</a>
                </li>
                @endif --}}
  
                @if (Auth::user()->role_id==1)              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'priorities')) active @endif">
                    <a href="{{ route('priorities.index') }}">@lang('main.sidebar.priorities')</a>
                </li>
                @endif
  
                @if (Auth::user()->role_id==1)              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'sla')) active @endif">
                    <a href="{{ route('sla.index') }}">@lang('main.sidebar.sla')</a>
                </li>
                @endif
  
  
  
              </ul>
              @endcan
  
            </div>
          </nav>

          <!-- Contenido principal -->
          <div class="col main-content">
            {{ $slot }} {{-- @yield('content') --}}
          </div>
          
        </div>

      </div>
    </main>


    {{-- <!-- Notification toast -->
    @if ( session('message') || session('error') || $errors->any() )
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" 
          data-delay="5000" style="position:absolute;top:1rem;right:1rem;opacity:1;z-index:1050;">
      @if ( session('message') )
        <div class="toast-header bg-success" style="height:1rem;">
      @else
        <div class="toast-header bg-danger" style="height:1rem;">
      @endif
      </div>
      <div class="toast-body">
        <div class="row">
          <div class="col-auto pt-1 mr-3">
          @if ( session('message') )
            {{ session('message') }}
          @elseif ( session('error') )
            {{ session('error') }}
          @else
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach 
          @endif          
          </div>
          <div class="col">
            <button type="button" class="ml-auto mb-1 close" data-dismiss="toast" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif --}}

    @php
      session()->forget('message');
      session()->forget('error');
    @endphp
  
  
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
  
    @stack('scripts')

    <script type="text/javascript">

      var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
      var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

      var themeToggleBtn = document.getElementById('theme-toggle');

      themeToggleBtn.addEventListener('click', function() {

        // if set via local storage previously
        if (localStorage.getItem('color-theme'))
        {
            if (localStorage.getItem('color-theme') === 'light') {
                localStorage.setItem('color-theme', 'dark');
                currentTheme = 'dark';
            } else {
                localStorage.setItem('color-theme', 'light');
                currentTheme = 'light';
            }

        // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('color-theme', 'light');
                currentTheme = 'dark';
            } else {
                localStorage.setItem('color-theme', 'dark');
                currentTheme = 'light';
            }
        }

        changeIconTheme();
          
      });

      function changeIconTheme()
      {
        if (currentTheme == 'dark') {
          document.documentElement.classList.add('dark');
          themeToggleDarkIcon.classList.remove('visible');
          themeToggleLightIcon.classList.add('visible');
        } else {
          document.documentElement.classList.remove('dark');
          themeToggleDarkIcon.classList.add('visible');
          themeToggleLightIcon.classList.remove('visible');
        }
      }


      $(document).ready(function () {

        changeIconTheme();

          /* $('.toast').toast('show') */

          $('#main-header').on('click', function () {
            if ($('#admin-submenu').hasClass('show'))
            {
              $('#main-header').addClass('expanded')
              $('#admin-header').removeClass('expanded')
              $('#main-header > i').removeClass('fa-chevron-down')
              $('#main-header > i').addClass('fa-chevron-up')
              $('#admin-header > i').removeClass('fa-chevron-up')
              $('#admin-header > i').addClass('fa-chevron-down')
              $('#main-link').trigger('click')
              $('#admin-link').trigger('click')
            }
          });

          $('#admin-header').on('click', function () {
            if ($('#main-submenu').hasClass('show'))
            {
              $('#admin-header').addClass('expanded')
              $('#main-header').removeClass('expanded')
              $('#admin-header > i').removeClass('fa-chevron-down')
              $('#admin-header > i').addClass('fa-chevron-up')
              $('#main-header > i').removeClass('fa-chevron-up')
              $('#main-header > i').addClass('fa-chevron-down')
              $('#main-link').trigger('click')
              $('#admin-link').trigger('click')
            }
          });

          $('.dismiss').on('click', function () {
              if ($('.sidebar').hasClass("active"))
              {
                  $('.sidebar').removeClass('active');
                  $('.overlay').removeClass('active');
              }
              else
              {
                  $('.sidebar').addClass('active');
                  $('.overlay').addClass('active');
              }
          });

          $('.overlay').on('click', function () {
              $('.sidebar').removeClass('active');
              $('.overlay').removeClass('active');
          });

          $('#perfil').on('click', function () {
            javascript:window.location.href='{{ route("user.profile", Auth::user()->id) }}';
          });

          $('#salir').on('click', function () {
            javascript:window.location.href='{{ route("logout") }}';
          });

          $('#maincontent').addClass('loaded');

      });

    </script>
  
  
  </body>

</html>