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
    
    
    <!-- Menu de navegacion -->
    <nav class="navbar" style="z-index: 8;">
        <div class="d-flex">
            <span class="d-inline d-sm-none dismiss fa fa-bars text-white ml-3 mr-1" style="margin-top:1.2rem;"></span>
            <a href="/"><li class="navbar-brand text-white">
              @lang('main.title')</li></a>
        </div>

        <div class="d-flex" style="margin:0; padding:0; top:-1rem;">

          <span class="nav-btn" style="padding:.45rem.75rem;margin-right:1rem;" id="theme-toggle">
            <i class="fa fa-sun" id="theme-toggle-light-icon"></i>
            <i class="fa fa-moon" id="theme-toggle-dark-icon" hidden></i>
          </span>
         

        </div>

    </nav>
    

    <main>
      <div class="">

          <!-- Contenido principal -->
          <div class="d-flex" style="flex-direction:column;justify-content:center;height:calc(100vh - 5rem);">
            @yield('content')
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
          themeToggleDarkIcon.setAttribute('hidden', true);
          themeToggleLightIcon.removeAttribute('hidden');
        } else {
          document.documentElement.classList.remove('dark');
          themeToggleLightIcon.setAttribute('hidden', true);
          themeToggleDarkIcon.removeAttribute('hidden');
        }
      }


      $(document).ready(function () {

        changeIconTheme();

          $('.toast').toast('show')


      });

    </script>
  
  
  </body>

</html>