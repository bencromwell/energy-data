<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/foundation.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Energy Data @yield('title')</title>
</head>
<body>
    <div class="fixed contain-to-grid">
        <nav class="top-bar" data-topbar>
            <ul class="title-area">
                <li class="name">
                    <h1><a href="{{ route('index') }}">Energy Data</a></h1>
                </li>
                <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
            </ul>

            <section class="top-bar-section">
                <ul class="right show-for-medium-up">
                    <li>
                        <a href="#">
                            E <span class="label">{{ $lastElectricity }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            G <span class="label">{{ $lastGas }}</span>
                        </a>
                    </li>
                </ul>

                <ul class="left">
                    <li {{ URL::route('index') === URL::current() ? ' class="active"' : '' }}>
                        <a class="" href="{{ route('index') }}">
                            Submit Readings
                        </a>
                    </li>
                    <li {{ URL::route('overall') === URL::current() ? ' class="active"' : '' }}>
                        <a class="" href="{{ route('overall') }}">
                            Overall Data
                        </a>
                    </li>
                    <li {{ URL::route('last-reading') === URL::current() ? ' class="active"' : '' }}>
                        <a class="" href="{{ route('last-reading') }}">
                            Last Reading
                        </a>
                    </li>
                    <li {{ URL::route('monthly') === URL::current() ? ' class="active"' : '' }}>
                        <a class="" href="{{ route('monthly') }}">
                            Monthly
                        </a>
                    </li>
                    <li {{ URL::route('tariff') === URL::current() ? ' class="active"' : '' }}>
                        <a class="" href="{{ route('tariff') }}">
                            Tariff
                        </a>
                    </li>
                </ul>
            </section>
        </nav>
    </div>
    <div class="ui page grid" id="container">
        @yield('content')
    </div>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/highcharts.js"></script>
    <script src="js/energy.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
