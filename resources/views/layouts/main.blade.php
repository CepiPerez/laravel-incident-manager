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
    <link href="{{ asset('assets/remixicon/remixicon.css') }}" rel="stylesheet">

    @stack('css')
    
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ticketera.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style3.css') }}" rel="stylesheet">
    

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
    <nav class="navbar" style="z-index: 8;">
        <div class="d-flex">
            <span class="d-inline d-sm-none dismiss fa fa-bars text-white ml-3 mr-1" style="margin-top:1.2rem;"></span>
            <a href="/"><li class="navbar-brand text-white">
              @lang('main.title')</li></a>
        </div>

        <div class="d-flex" style="margin:0; padding:0; top:-1rem;">

          <span class="nav-btn theme-toggle" style="padding:.45rem .65rem;margin-right:1rem;width:34px;height:34px;" id="theme-toggle">
            <i class="ri-lg ri-sun-line" style="line-height:21px;font-size:15px;vertical-align:middle;" id="theme-toggle-light-icon" hidden></i>
            <i class="ri-lg ri-moon-line" style="line-height:21px;font-size:15px;vertical-align:middle;" id="theme-toggle-dark-icon" hidden></i>
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
  

              <ul class="list-unstyled mt-3" id="main-submenu">
  
                <li class="sidebaritem @if(Route::currentRouteName()=='incidents.index' || Route::currentRouteName()=='incidents.edit') active @endif">
                    <a href="{{ route('incidents.index') }}">
                      <i class="ri-chat-4-line"></i><span>@lang('main.sidebar.incidents')</span>
                    </a>
                </li>
  
                @can('crear_inc')
                <li class="sidebaritem @if(Route::currentRouteName()=='incidents.create') active @endif">
                    <a href="{{ route('incidents.create') }}">
                      <i class="ri-chat-new-line"></i><span>@lang('main.sidebar.new_incident')</span>
                    </a>
                </li>
                @endcan
  
                {{-- @can('carga_masiva')
                <li class="sidebaritem @if(Route::currentRouteName()=='cargamasiva') active @endif">
                    <a href="{{ route('cargamasiva') }}">@lang('main.sidebar.mass_creation')</a>
                </li>
                @endcan --}}
  
                @can('tablero_control')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'dashboard')) active @endif">
                    <a href="{{ route('dashboard.index') }}">
                      <i class="ri-dashboard-3-line"></i><span>@lang('main.sidebar.dashboard')</span>
                    </a>
                </li>
                @endcan
  
                @can('informes')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'reports')) active @endif">
                    <a href="{{ route('reports.index') }}">
                      <i class="ri-bar-chart-box-line"></i><span>@lang('main.sidebar.reports')</span>
                    </a>
                </li>
                @endcan
              
              </ul>
  
              @can ('admin_panel')
              
              <ul class="list-unstyled mt-2" id="admin-submenu">
  
                @can ('admin_usuarios')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'users')) active @endif">
                    <a href="{{ route('users.index') }}">
                      <i class="ri-user-line"></i><span>@lang('main.sidebar.users')</span>
                    </a>
                </li>
                @endcan
  
                @can ('admin_usuarios')
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'groups')) active @endif">
                    <a href="{{ route('groups.index') }}">
                      <i class="ri-group-line"></i><span>@lang('main.sidebar.groups')</span>
                    </a>
                </li>
                @endcan
  
                @can ('admin_roles')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'roles')) active @endif">
                  <a href="{{ route('roles.index') }}">
                    <i class="ri-door-lock-box-line"></i><span>@lang('main.sidebar.roles')</span>
                  </a>
                </li>
                @endcan
                
                @can ('admin_clientes')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'clients')) active @endif">
                    <a href="{{ route('clients.index') }}">
                      <i class="ri-account-box-line"></i><span>@lang('main.sidebar.clients')</span>
                    </a>
                </li>
                @endcan
                
                @can ('admin_areas')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'areas')) active @endif">
                    <a href="{{ route('areas.index') }}">
                      <i class="ri-aspect-ratio-line"></i><span>@lang('main.sidebar.areas')</span>
                    </a>
                </li>
                @endcan
                
                @can ('admin_modulos')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'modules')) active @endif">
                    <a href="{{ route('modules.index') }}">
                      <i class="ri-picture-in-picture-line"></i><span>@lang('main.sidebar.modules')</span>
                    </a>
                </li>
                @endcan
                
                @can ('admin_tipoincidente')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'problems')) active @endif">
                    <a href="{{ route('problems.index') }}">
                      <i class="ri-settings-6-line"></i><span>@lang('main.sidebar.problems')</span>
                    </a>
                </li>
                @endcan
                
                @can ('admin_tiposervicio')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'servicetypes')) active @endif">
                    <a href="{{ route('servicetypes.index') }}">
                      <i class="ri-settings-line"></i><span>@lang('main.sidebar.service_types')</span>
                    </a>
                </li>
                @endcan
                
                @can ('admin_tipoavance')              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'progresstypes')) active @endif">
                    <a href="{{ route('progresstypes.index') }}">
                      <i class="ri-share-forward-box-line"></i><span>@lang('main.sidebar.progress_types')</span>
                    </a>
                </li>
                @endcan
  
                {{-- @if (Auth::user()->rol_id==1)              
                <li class="sidebaritem @if(Route::currentRouteName()=='reglas') active @endif">
                    <a href="{{ route('reglas') }}">@lang('main.sidebar.rules')</a>
                </li>
                @endif --}}
  
                @if (Auth::user()->role_id==1)              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'priorit')) active @endif">
                    <a href="{{ route('priorities.index') }}">
                      <i class="ri-arrow-up-circle-line"></i><span>@lang('main.sidebar.priorities')</span>
                    </a>
                </li>
                @endif
  
                @if (Auth::user()->role_id==1)              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'sla')) active @endif">
                    <a href="{{ route('sla.index') }}">
                      <i class="ri-time-line"></i><span>@lang('main.sidebar.sla')</span>
                    </a>
                </li>
                @endif
  
                @if (Auth::user()->role_id==1)              
                <li class="sidebaritem @if(Str::contains(Route::currentRouteName(),'assignation')) active @endif">
                    <a href="{{ route('assignation.index') }}">
                      <i class="ri-user-received-line"></i><span>@lang('main.sidebar.assignation')</span>
                    </a>
                </li>
                @endif
  
  
              </ul>
              @endcan
  
            </div>
          </nav>

          <div class="expand">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>
          </div>
  

          <!-- Contenido principal -->
          <div class="col main-content">
            @yield('content')
          </div>
          
        </div>

      </div>
    </main>


    <!-- Notification toast -->
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
    @endif

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
      //console.log("THEME: "+currentTheme);

      if (currentTheme == 'dark') {
        document.documentElement.classList.add('dark');
        themeToggleDarkIcon.setAttribute('hidden', true);
        themeToggleLightIcon.removeAttribute('hidden');
      } else {
        document.documentElement.classList.remove('dark');
        themeToggleLightIcon.setAttribute('hidden', true);
        themeToggleDarkIcon.removeAttribute('hidden');
      }
    }

      function setLoaded() {
        $('#maincontent').addClass('loaded');
        //$('#background').addClass('loaded');
      }

      $(document).ready(function () {

        changeIconTheme();

        $('.toast').toast('show')


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

        $('.expand').on('click', function () {
          if ($('.sidebar').hasClass("collapsed"))
          {
            $('.sidebar').removeClass('collapsed');
            $('.background').removeClass('collapsed');
            localStorage.setItem('sidebar', 'expanded');
          }
          else
          {
            $('.sidebar').addClass('collapsed');
            $('.background').addClass('collapsed');
            localStorage.setItem('sidebar', 'collapsed');
          }
        });


        if (localStorage.getItem('sidebar') === 'collapsed') {
          $('.sidebar').addClass('collapsed');
          $('.background').addClass('collapsed');
        }

        setTimeout(setLoaded, 600);

      });

    </script>
  
  
  </body>

</html>