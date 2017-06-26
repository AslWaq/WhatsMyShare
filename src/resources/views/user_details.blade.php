@extends('layouts.master')

@section('content')
<style>
.panel-default > .panel-heading {
    background-color: rgb(200,200,255);
}
</style>
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
  <div class="row">
    <h3 class="text-center" style= "color: #07889B">My Dashboard</h3>

    @if (Session::has('transMsg'))
      <div style="max-width: 250px" class="alert alert-info pull-right">
        {{Session::pull('transMsg')}}
      </div>
    @endif
  </div>

  <br>

  <div class="row">
    <!--div class="col-sm-3" style="background-color: white; border-color: black; border-style: solid; border-width: 0.5px"-->
      <div class="third">
        <div class="panel panel-primary">
          <div class="panel-heading">My Account</div>
          <div class="panel-body">
      <p><span>Liquid Cash Balance: </span><span style="color:green">${{Auth::user()->cash}}</span></p>
      <p><span>Invest Score: </span><span style="color:green">${{Auth::user()->invest_score}}</span></p>
    </div>
  </div>
</div>
    <!--div class="col-sm-3 col-sm-offset-1" style="background-color: white; border-color: black; border-style: solid; border-width: 0.5px"-->

      <div class="third">
        <div class="panel panel-primary">
          <div class="panel-heading">My Portfolio</div>
          <div class="panel-body">
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
  </div>
    </div>
    <!--div class="col-sm-3 col-sm-offset-1" style="background-color: white; border-color: black; border-style: solid; border-width: 0.5px"-->
      <div class="third">
        <div class="panel panel-primary">
          <div class="panel-heading">My Shorted Stocks</div>
          <div class="panel-body">
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
                                          <td><p>Price: <span class="label label-success">$ {{$shortsArray[$short->stock_ticker][0]}}</span></p></td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <p>Gain: <span class="label label-success">{{$shortsArray[$short->stock_ticker][1]}}%</span></p>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td><p>Date shorted: <span class="label label-danger">{{$short->shorted_at}}</span></p></td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <a type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal{{$short->stock_ticker}}">Pay Back</a>
                                              <!-- Modal -->
                                              <div id="myModal{{$short->stock_ticker}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                  <!-- Modal content-->
                                                  <div class="modal-content"  style="color: black">
                                                    <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      <h4 class="modal-title">Pay back {{$short->stock_ticker}} Shares</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                      <p>Are you sure you want to pay back these stocks?</p>
                                                      <p>The gains or losses you have made on these short will be reflected on your cash balance</p>

                                                    </div>
                                                    <div class="modal-footer">
                                                      <a href="/payback-shorts/{{$short->stock_ticker}}/{{$shortsArray[$short->stock_ticker][0]}}" type="button" class="btn btn-primary">Continue</a>
                                                      <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
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
  </div>
</div>
  </div>
  <br><br>

</div>
@endsection
