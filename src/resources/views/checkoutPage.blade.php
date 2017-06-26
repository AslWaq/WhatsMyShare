@extends('layouts.master')

@section('content')

<h3 class="text-center" style= "color: #07889B">Your Shopping Cart</h3>

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
            $('#'+ data[0]).append(data[1]);


          });

    }


});
function btnE(ticker){
  $('#button'+ticker).attr("disabled", false);
  $('#button'+ticker).text($('input[name=action' + ticker + ']:checked').val());
  CalcCost(ticker);
}
function CalcCost(ticker){
  var cost = $('#'+ticker).text() * $('#action'+ticker).val();
  $('#cost'+ticker).text(cost);
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
      alert(taction+stockN);
    }

  }
  else{
    alert('tac');

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

						<div class="col-md-6">
              <div class="col-md-6 text-right">
							<h4 class="product-name"><strong>{{$item[0]}}</strong></h4><h4><small id="{{ $item[0] }}" onload="getP('{{ $item[0] }}')"></small></h4>
            </div>
            <div class="col-md-6 text-right">
              <h4 class="product-name"><strong>Action</strong></h4>

              <form >
                  <input onclick="btnE('{{$item[0]}}')" type="radio" name="action{{$item[0]}}" value="buy"> Buy
                  <input onclick="btnE('{{$item[0]}}')" type="radio" name="action{{$item[0]}}" value="short"> Short

              </form>

            </div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 text-right">
								<h6><strong>Number of shares</strong></h6>
                <small>cost</small>
							</div>
							<div class="col-md-4">
								<input onkeyup="CalcCost('{{$item[0]}}')"id="action{{$item[0]}}" type="number" min="1" class="form-control" value="1">
                <small id="cost{{$item[0]}}"></small>
							</div>
              <div class="col-md-2">
								<button disabled id="button{{$item[0]}}" onclick="plOrder('{{$item[0]}}')" type="button" class="btn btn-primary">
                  Buy
								</button>
							</div>
							<div class="col-md-2">
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

							<div class="col-xs-3">
								<a href="/search-stocks" type="button" class="btn btn-primary btn-sm btn-block">Search More Stocks</a>
							</div>

						</div>
					</div>


<br>
<br>
	</div>
</div>
@endsection
