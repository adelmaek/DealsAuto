<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html>
<head>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <title> Deals Auto</title>
    {{-- Styles and scripts for sidenavbar --}}
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/sideNavBar.css') }}">
    {{-- end of styles and scripts for sidenavbar --}}

</head>
<body>
    <div class="wrapper">
        <nav id="sidebar" >
            <div class="sidebar-header">
                <h3>Deals Auto</h3>
            </div>
            <ul class="lisst-unstyled components">
                <p>The Providers</p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" >Unused Button</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu" >
                        <li>
                            <a href="#">Unused Button 1</a>
                        </li>
                        <li>
                            <a href="#">Unused Button 2</a>
                        </li>
                    </ul>
                    
                </li>
                <li>
                    <a href="#">Unused Button</a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Unused Button</a>
                    <ul class="collapse lisst-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#">Unused Button 1</a>
                        </li>
                        <li>
                            <a href="#">Unused Button 2</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Unused Button</a>
                </li>
                <li>
                    <a href="#">Unused button</a>
                </li>
            </ul>


        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Menu</span>
                    </button> 
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                            <a id="UserNameDropList" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->fullname }} <span class="caret"></span>
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
                    </ul> 
                </div>
            </nav>
        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $("#sidebarCollapse").on('click',function(){
                $("#sidebar").toggleClass('active');
            })
            $('#sidebar ul li').on('click', function () {
                 $('#sidebar ul .active').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>

</body>
</html>
