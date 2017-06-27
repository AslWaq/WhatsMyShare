<!DOCTYPE html>
<html lang="en">
<head>
  <title>WhatsMyShare</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
  <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <!--<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script><-->
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */
    .ui-autocomplete {
    max-height: 250px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
    /* add padding for vertical scrollbar */
    padding-right: 5px;
  }

  .ui-autocomplete li {
    font-size: 13px;
  }

  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
      color: rgb(200,200,200);

    }
    .glyphicon.glyphicon-shopping-cart{
    font-size: 20px;
    }
    .navbar-inverse {
    padding-right: 0px;
    margin-right: -14px;
    }
    .navbar-inverse .navbar-brand {
      color: rgb(180,180,180);
      font-weight: bolder;
    }
    .navbar-inverse .navbar-nav > li > a {
      color: rgb(180,180,180);
      font-weight: bolder;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
    body {
      background-color: white;
    }
    .third {
      float: left;
      width: 100%;
      width: 33.3333%;
      min-width: 400px;
      padding: 0 8px;
    }

  </style>
</head>
<body>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=1426821647341174";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>


<nav class="navbar navbar-inverse" style="background-color:rgb(58,58,58)">

  <div class="container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="/dashboard"><img class="logo pull-left" style="margin-right: 20px" src="/wmslogo.png" width="80" height="50"></a>
      <a class="navbar-brand" href="/dashboard">My Account</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="/search-stocks">Stock Trading</a></li>
        <li><a href="/leaderboard">Leaderboard</a></li>
          @if (!Auth::guest())
            <li><a href="/my-cart"><span class="glyphicon glyphicon-shopping-cart"></span><span id="cart-content">{{ count(json_decode(Auth::user()->shopping_cart,true)) }}</span></a></li>
          @endif
      </ul>

      <ul class="nav navbar-nav navbar-right">
        @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @else

          <li style="margin-right: 20px">Cash: <span class="label label-primary">${{Auth::User()->cash}}</span></li>
            <li style="margin-right: 20px">Invest Score: <span class="label label-primary">${{Auth::user()->invest_score}}</span></li>



<!--
          <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">

                    {{ Auth::user()->name }} <span class="caret"></span>

                </a>
              -->
                <!--<ul class="dropdown-menu" role="menu">-->
                    <!--<li>-->
                      {{ Auth::user()->name }}
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            <span class="glyphicon glyphicon-log-out" style="padding-left: 5px"></span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        @if (Auth::user()->facebook_user_id)
                          <img src="https://graph.facebook.com/{{ Auth::user()->facebook_user_id }}/picture?width=70&height=50">
                        @endif
                    <!--</li>-->
                <!--</ul>-->

        @endif
      </ul>
    </div>
  </div>
</nav>
@yield('content')
</body>
