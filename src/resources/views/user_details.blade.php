@extends('layouts.master')

@section('content')
<script>
function activateBtn(ticker){
  console.log($('#shares'+ticker).val());
  if(($('#shares'+ticker).val()) > 0){
    console.log($('#shares'+ticker).val() > 1);
    $('#button'+ticker).attr("disabled", false);
  }else{
    $('#button'+ticker).attr("disabled", true);
  }
};

function sellShares(ticker, price){
  var shares = $('#shares'+ticker).val();
  var ord = [];
  ord = [ticker, shares, price];
  var order = JSON.stringify(ord);

  $.get('sell/' + order, function(data){
        console.log(data);


    });
    location.reload();




};
</script>
<!-- Container (Services Section) -->
<div class="container-fluid " style="margin: auto; width: 96%">
  <h2 style="color:white">My Dashboard</h2>

  <br>
  <div class="row">
    <div class="col-sm-3" style="background-color:black; color:white">

      <h4 style="background-color: grey; padding: 5px;">MY ACCOUNT SUMMARY</h4>
      <p><span>Liquid Cash Balance: </span><span style="color:green">${{Auth::user()->cash}}</span></p>
      <!--<p><span>Portfolio Value: </span><span style="color:green">${{$investValue}}</span></p>-->
      <p><span>Invest Score: </span><span style="color:green">${{Auth::user()->invest_score}}</span></p>
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

                                              <!-- Trigger the modal with a button -->
                                              <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal{{$stock->stock_ticker}}">Sell</button>

                                              <!-- Modal -->
                                              <div id="myModal{{$stock->stock_ticker}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                  <!-- Modal content-->
                                                  <div class="modal-content"  style="color: black">
                                                    <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      <h4 class="modal-title">Sell {{$namearray[$i]}} Shares</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                      <p>How many shares do you want to sell?</p>
                                                      <form class="form-inline">

                                                        <input id="shares{{$stock->stock_ticker}}" onkeyup="activateBtn('{{$stock->stock_ticker}}')" onchange="activateBtn('{{$stock->stock_ticker}}')" class="form-control" name="stockSelling" type="number">
                                                        <span><a id="button{{$stock->stock_ticker}}" onclick="sellShares('{{$stock->stock_ticker}}','{{$prices[$stock->stock_ticker]}}')" class="btn btn-primary" disabled>Sell</a></span>
                                                      </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                  </div>

                                                </div>
                                              </div>
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
