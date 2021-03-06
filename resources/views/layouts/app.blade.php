<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script ="{{ asset('js/app.js') }}" defer></script>
	 
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
	
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	
	
	
	<!-- Bootsrap -->
	<!-- Latest compiled and minified CSS -->
    <!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">-->

    <!-- Latest compiled and minified JavaScript -->
    <!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->  
	<!-- Bootsrap -->
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
	
	<!-- Fa Library -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<script src="{{ asset('js/LightBox/lightbox.js') }}"></script>           <!-- LightBox Lib JS  -->
    <link  href="{{ asset('css/LightBox/lightbox.css') }}" rel="stylesheet"> <!-- LightBox Lib CSS -->
	
	
	<!-- Mine Bootstrap -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
	
	
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
					
					    <!-- Common Links -->
					    <li class="nav-item {{ Request::is('/*') ? 'active' : '' }}">              <a  class="nav-link" href="{{ route('welcome') }}">        Welcome  </a></li>
						<li class="nav-item {{ Request::is('crud-simple*') ? 'active' : '' }}">    <a  class="nav-link" href="{{ route('crud-simple') }}">    CRUD     </a></li>
                        <li class="nav-item {{ Request::is('vue-crud-panel*') ? 'active' : '' }}"> <a  class="nav-link" href="{{ route('vue-crud-panel') }}"> Vue Crud </a></li>

						
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item {{ Request::is('login*') ? 'active' : '' }}"> 
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item {{ Request::is('register*') ? 'active' : '' }}"> 
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
	
	
	<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->                <!-- Mega Fix (collapsed main menu won't open)-->	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script>     <!-- Sweet Alert JS--> 
	
	
	<!-- To register JS file for specific view only (In layout template) -->
    @if (in_array(Route::getFacadeRoot()->current()->uri(), ['createNewWpressImg', 'gii-edit-post/{id}', 'wpBlogImagesOne/{id}'])) <!--Route::getFacadeRoot()->current()->uri()  returns testRest--> 
        <script src="{{ asset('js/Crud_Simple/crud_simple.js') }}"></script>      <!-- Crud simpple JS JS  -->
    @endif	
	
	
	
	 <!-- Vue js. To register JS file for specific view only (In layout template) -->
    @if (in_array(Route::getFacadeRoot()->current()->uri(), ['vue-crud-panel'])) <!--Route::getFacadeRoot()->current()->uri()  returns testRest--> 
        <script src="{{ asset('js/Vue_crud_panel/vue_crud_panel_start.js') }}"></script>      <!-- Crud simpple JS JS  -->
		<link  href="{{ asset('css/Third_party_app_css/Element_UI/theme-chalk/index.css') }}" rel="stylesheet"> <!-- Elememt-UI icons (fix)  -->
    @endif
	
	
</body>
</html>
