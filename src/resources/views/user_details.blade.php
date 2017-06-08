@extends('layouts.master')

@section('content')
<!-- Container (Services Section) -->
<div class="container-fluid text-center">
  <h2 style="color:white">My Dashboard</h2>

  <br>
  <div class="row">
    <div class="col-sm-3" style="background-color:black; color:white">

      <h4>MY ACCOUNT SUMMARY</h4>
      <p><span>Liquid Cash Balance: </span><span style="color:green"> {{Auth::user()->cash}}</span></p>
    </div>
    <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">

      <h4>MY PORTFOLIO</h4>
      @foreach ($portfolio as $stock)
          <p>{{$stock->stock_ticker}} <span>{{$stock->shares}}</span></p>
      @endforeach

    </div>
    <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">

      <h4>MY SHORTED STOCKS</h4>
      <p>Lorem ipsum dolor sit amet..</p>
    </div>
  </div>
  <br><br>

</div>
@endsection
