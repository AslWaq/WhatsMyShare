@extends('layouts.master')

@section('content')
<script>
function getP(ticker){


  $.get('get-price/' + ticker, function(data){
        document.getElementById("jh").innerHtml = data;
        console.log(data);
      });
    }
</script>
<div class="container" style="background-color: white">

<br>
<br>
  @foreach(json_decode(Auth::user()->shopping_cart) as $item)
					<div class="row">

						<div class="col-md-6">
              <div class="col-md-6 text-right">
							<h4 class="product-name"><strong>{{$item}}</strong></h4><h4><small id="{{ $item }}" onload="getP('{{ $item }}')">price: USD 49.05</small></h4>
            </div>
            <div class="col-md-6 text-right">
              <h4 class="product-name"><strong>Action</strong></h4>

              <form action="">
                  <input type="radio" name="action" value="buy"> Buy
                  <input type="radio" name="action" value="short"> Short

              </form>

            </div>
						</div>
						<div class="col-md-6">
							<div class="col-md-4 text-right">
								<h6><strong>Number of shares</strong></h6>
                <small>cost</small>
							</div>
							<div class="col-md-4">
								<input type="number" min="1" class="form-control" value="1">

							</div>
              <div class="col-md-2">
								<button type="button" class="btn btn-primary">
                  Buy
								</button>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-link btn-xs">
									<span class="glyphicon glyphicon-trash"> </span>
								</button>
							</div>
						</div>
					</div>
					<hr>
          @endforeach
					<div class="row">
						<div class="text-center">

							<div class="col-xs-3">
								<button type="button" class="btn btn-primary btn-sm btn-block">Update cart</button>
							</div>
              <div class="col-xs-3">
								<button id="jh" onClick="(getP('FB'))" type="button" class="btn btn-primary btn-sm btn-block">{{ Auth::user()->shopping_cart }}</button>
							</div>
						</div>
					</div>


<br>
<br>
	</div>
</div>
@endsection
