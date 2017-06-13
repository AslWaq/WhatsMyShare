@extends('layouts.master')

@section('content')
<!-- Container (Services Section) -->
<div class="container-fluid ">
  <h2 style="color:white">My Dashboard</h2>

  <br>
  <div class="row">
    <div class="col-sm-3" style="background-color:black; color:white">

      <h4>MY ACCOUNT SUMMARY</h4>
      <p><span>Liquid Cash Balance: </span><span style="color:green">${{Auth::user()->cash}}</span></p>
      <p><span>Portfolio Value: </span><span style="color:green">${{$investValue}}</span></p>
      <p><span>Invest Score: </span><span style="color:green">${{$investValue + Auth::user()->cash}}</span></p>
    </div>
    <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">

      <h4>MY PORTFOLIO</h4>
      @php ($i = 0)
      @foreach ($portfolio as $stock)
      <div class="container">
          <div class="row">
              <div class="col-sm-3 col-md-3">
                  <div class="panel-group" id="accordion">
                      <div class="panel panel-default">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}">
                                    <p>{{$namearray[$i]}}</p></a>
                              </h4>
                          </div>
                          <div id="collapse{{$i}}" class="panel-collapse collapse">
                              <div class="panel-body">
                                  <table class="table">
                                    <tr>
                                        <td><p>Shares: {{$stock->shares}}</p></td>
                                    </tr>
                                      <tr>
                                          <td><p>Price: <span class="label label-success">${{$prices[$stock->stock_ticker]}}</span></p></td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <p>Value: <span class="label label-success">${{$stock->shares * $prices[$stock->stock_ticker]}}</span></p>
                                          </td>
                                      </tr>

                                      <tr>
                                          <td>
                                              <a href="#">Sell</a>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          </div>
            @php ($i++)
      @endforeach

    </div>
    <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">

      <h4>MY SHORTED STOCKS</h4>
      @php ($j = 0)
      @foreach (Auth::user()->shorts as $short)
      <div class="container">
          <div class="row">
              <div class="col-sm-3 col-md-3">
                  <div class="panel-group" id="accordion">
                      <div class="panel panel-default">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}">
                                    <p>{{$short->stock_ticker}}</p></a>
                              </h4>
                          </div>
                          <div id="collapse{{$i}}" class="panel-collapse collapse">
                              <div class="panel-body">
                                  <table class="table">
                                    <tr>
                                        <td><p>Shares: {{$short->shares}}</p></td>
                                    </tr>
                                      <tr>
                                          <td><p>Price: <span class="label label-success">$</span></p></td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <p>Gain: <span class="label label-success"></span></p>
                                          </td>
                                      </tr>

                                      <tr>
                                          <td>
                                              <a href="#">Pay Back</a>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          </div>
            @php ($i++)
      @endforeach
    </div>
  </div>
  <br><br>

</div>
@endsection
