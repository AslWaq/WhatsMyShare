@extends('layouts.master')

@section('content')

<h3 class="text-center" style= "color: #07889B">Your Shopping Cart</h3>
@if (Session::has('shortErrorMsg'))
  <div class="alert alert-info pull-right">
    {{Session::pull('shortErrorMsg')}}
  </div>
  <br>
@endif
<br>
<script>
$( document ).ready(function(){
  var sc = {!! Auth::user()->shopping_cart !!};
  var ticker;
  var i = 0;
  var pr = 0;
  for(i = 0; i<sc.length; i++){
    ticker = JSON.parse(sc[i])[0];
    console.log(JSON.parse(sc[i])[0]);
      $.get('get-price/' + ticker, function(data){
            $('#'+ data[0]).append(data[1].toFixed(2));


          });

    }


});
function btnE(ticker){
  $('#button'+ticker).attr("disabled", false);
  $('#button'+ticker).text($('input[name=action' + ticker + ']:checked').val());
  CalcCost(ticker);
}
function CalcCost(ticker){
  var yourCash = {!! Auth::user()->cash !!};

  var cost = $('#'+ticker).text() * $('#action'+ticker).val();
  if(yourCash >= cost){
    $('#cost'+ticker).css({"color": "inherit"});
    $('#cost'+ticker).text("$" + cost.toFixed(2));
    if( ($('input[name=action' + ticker + ']:checked').val()) )
      $('#button'+ticker).attr("disabled", false);
  }else{
    if( ($('input[name=action' + ticker + ']:checked').val() == "Short") ){
      $('#cost'+ticker).text("$" + cost.toFixed(2));
    }else{
      $('#cost'+ticker).text("$" + cost.toFixed(2) + " is higher than your cash balance");
      $('#cost'+ticker).css({"color": "rgb(220,100,100)"});
      $('#button'+ticker).attr("disabled", true);
    }
  }
}

function plOrder(ticker){
  var taction = $('input[name=action' + ticker + ']:checked').val();
  var stockN = $('#action'+ticker).val();
  var ord = [];
  if (taction){
    if (stockN){

      var pr = $('#'+ticker).text();
      ord = [ticker, stockN, pr];
      var order = JSON.stringify(ord);

      $.get(taction + '/' + order, function(data){
          console.log(data);


          });
        location.reload();
    }else{
      alert("input the number of shares");
    }

  }
  else{
    alert('choose action please');

  }
};
function delItem(ticker){
  $.get('del-item/' + ticker, function(data){
      console.log(data);


      });
    location.reload();

};
</script>

<div class="container" style="background-color: white">
<br>
<br>
  @foreach(json_decode(Auth::user()->shopping_cart) as $item)
  @php( $item = json_decode($item))
					<div class="row {{$item[0]}}">

						<div class="cartSection1">
                <div class="prod-name">
    							<h4 class="product-name"><strong>{{$item[0]}}</strong></h4><p>$<span id="{{ $item[0] }}" onload="getP('{{ $item[0] }}')"></span></p>
                </div>
                <div class="chosen-act">
                  <h4 class="product-name"><strong>Action</strong></h4>

                  <form >
                      <input onclick="btnE('{{$item[0]}}')" type="radio" name="action{{$item[0]}}" value="Buy"> Buy
                      <input onclick="btnE('{{$item[0]}}')" type="radio" name="action{{$item[0]}}" value="Short"> Short

                  </form>

                </div>
						</div>

            <style>
              .cartSection2{
                float:left;
                width: 100%;
                width: 65%;
                min-width: 350px;
                padding-bottom: 10px;

              }
              .cartSection1 {
                float:left;
                width: 100%;
                width: 35%;
                min-width: 100px;
                padding-bottom: 10px;

              }
              .prod-name {
                float: left;
                width: 50%;
                padding-left: 5px;
              }
              .chosen-act {
                float: left;
                width: 50%;
                padding-left: 5px;
              }
              .del-button {
                float: left;
                width: 15%;
                padding-left: 5px;
              }
              .act-button {
                float: left;
                width: 15%;
                padding-left: 5px;
              }
              .shares-wanted {
                float: left;
                width: 35%;
                padding-left: 5px;
              }
              .shares-input {
                float: left;
                width: 35%;
                padding-left: 5px;
              }
            </style>

						<div class="cartSection2 centre-block">
							<div class="shares-wanted text-right">
								<h6><strong>Number of shares</strong></h6>
                <p>Cost</p>
							</div>
							<div class="shares-input">
								<input style="max-width: 120px" onkeyup="CalcCost('{{$item[0]}}')" onchange="CalcCost('{{$item[0]}}')" id="action{{$item[0]}}" type="number" min="1" class="form-control" value="1">
                <p id="cost{{$item[0]}}"></p>
							</div>
              <div class="act-button">
								<button disabled id="button{{$item[0]}}" onclick="plOrder('{{$item[0]}}')" type="button" class="btn btn-primary">
                  Buy
								</button>
							</div>
							<div class="del-button pull-right">
								<button onclick="delItem('{{$item[0]}}')" type="button" class="btn btn-link btn-xs">
									<span class="glyphicon glyphicon-trash"> </span>
								</button>
							</div>
						</div>
            <hr>
					</div>
					<hr>
          @endforeach
					<div class="row">
						<div class="text-center">

							<div class="col-md-3">
								<a href="/search-stocks" type="button" class="btn btn-primary btn-sm btn-block">Search More Stocks</a>
							</div>

						</div>
					</div>


<br>
<br>
	</div>
</div>
@endsection
