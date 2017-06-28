@extends('layouts.master')

@section('content')
<style>
  .bg-1 {
      background-color: rgb(240, 240, 240); /* Green */
      color: rgb(0,0,0);
      padding: 20px;
  }
  .bg-2 {
      background-color: rgb(70, 173, 212); /* Neon Blue */
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
    <h4><a href="/login">Login</a> or <a href="/register">Register</a> now</h4>
  @endif
</div>

<!-- Second Container -->
<div class="container-fluid bg-2 text-center">
  <h3 class="margin">About Us</h3>
  <p>WhatsMyShare is a web application which allows you to practice stock trading in a risk free game environment.
    Upon joining WhatsMyShare, you get an account containing $30,000 of virtual money. You may use this money to buy
    stocks and build a portfolio or you may also choose to short the stocks. The stocks you trade here are for companies
    listed on the S&P500 stock market index. We also provide charts showing company stock performance over the last six months
    as well as charts showing our users' investment scores. Over time you can watch your portfolio value grow (or depreciate)
    as the share prices fluctuate (using real market prices). You may also have shorting gains or losses depending on the share performance
    and the timeliness of your bet.</p>

  <p>Compete with other users on the site using your net worth (the value of your portfolio, shorting gains, and cash balance) and get an edge
    on the competition as you race to the top of the daily leaderboards. Follow users and track their progress or your facebook friends who use the site
    (Facebook account login required).
</div>

<!-- Third Container (Grid) -->
<div class="container-fluid bg-3 text-center">
  <h3 class="margin">How Shorting or Trading works</h3><br>
  <p>Shorting a stock is 'borrowing' a number of shares one day at a certain price, and then betting that the price
    will go lower in the future where you then buy the stocks at a lower price to pay back the number of shares you owe. The gain you made
    from buying low (the difference between how much you paid for the shares, and how much you borrowed them for) is a positive increase in your net worth.
    Shorting is basically betting that the stock will be "shorter", hence you borrow the shares now to get a profit. This is risky however in that if the stocks
    gain price then you will have to pay more than what you owed, hence being dealt with a short loss rather than a short gain.
    Short: Borrow high + Pay Back low = Profit. If you have not paid back your short after 90 days then we will automatically have it be paid back, whether or not it is a
    gain or loss.
    Trading stocks involves buying shares at a low price and selling when they get higher for profit. Or watch the price decrease in value and cause your
    portfolio value and invest score plummet in the process.
</div>

<!-- Footer -->
<footer class="container-fluid bg-4 text-center">

</footer>
@endsection
