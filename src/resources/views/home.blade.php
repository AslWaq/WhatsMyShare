@extends('layouts.master')

@section('content')
<style>
  .bg-1 {
      background-color: rgb(240, 240, 240); /* Green */
      color: rgb(0,0,0);
      padding: 20px;
  }
  .bg-2 {
      background-color: rgb(70, 173, 212); /* Dark Blue */
      color: #ffffff;
      padding: 20px;
      font-size: 18px;
  }
  .bg-3 {
      background-color: #ffffff; /* White */
      color: #555555;
      padding: 20px;
  }
  .bg-4 {
      background-color: #2f2f2f; /* Black Gray */
      color: #fff;
  }

  </style>
<!-- First Container -->
<div class="container-fluid bg-1 text-center">
  <h3 class="margin">WhatsMyShare</h3>
  <img src="wmslogo.png" class="img-responsive img-circle margin" style="display:inline; background-color: rgb(10,10,10)" alt="Bird" width="200" height="200">
  <h3>A website for people who would like to learn about about stock trading in a risk free and fun way</h3>
  @if (Auth::guest())
    <h4><a href="/login">Login</a> or <a href="/register">Register</a> and let the fun begins</h4>
  @endif
</div>

<!-- Second Container -->
<div class="container-fluid bg-2 text-center">
  <h3 class="margin">What The Site is About?</h3>
  <p>WhatsMyShare is a website where you can practice stock trading in a risk free game environment. When you join whatsMyShare, you get an account containing $30,000 of virtual money. You can use this money to buy stocks and build a portfolio. You can also short stocks on WhatsMyShare. The stocks you trade here are for real companies listed on the S&P500 stock market index. We provide for you charts showing stocks performances over the last six months. The stock prices shown here are the real prices of stocks. Over time, you can watch your portfolio value grow in real time. You can also make shorting gains or losses depending on the the performance of the stocks you have chosen to short</p>

<p>As a game, you get to compete with other users on the site using your net worth (the value of your portfolio, shorting gains, and cash balance) as your score. You can also see how you are performing against people you have chosen to follow or your facebook friends if you login to the site using your Facebook account.
</div>

<!-- Third Container (Grid) -->
<div class="container-fluid bg-3 text-center">
  <h3 class="margin">Where To Find Me?</h3><br>

</div>

<!-- Footer -->
<footer class="container-fluid bg-4 text-center">

</footer>
@endsection
