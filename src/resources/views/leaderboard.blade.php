@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<h3 class="text-center" style="color: black">LEADERBOARDS</h3>
<br>
@if (!($users->isEmpty()))
@php ($chartData = array())
@php ($chartLabels = array())
@foreach ($curUser->scores as $score)
  @php (array_push($chartData, $score->score))
  @php (array_push($chartLabels, $score->date))

@endforeach

<script>
$(document).ready(function() {
    $('#example').DataTable( {
      ordering: false,
      "bSort": false
    } );
} );

function usrProf(id){
  var i;
  console.log(id);
  $.get('usr-prof/' + id, function(data){

    console.log(data[1].length);
    for (i=0; i<data[1].length; i++){
      $('#port').append(data[1][i].stock_ticker);
    }
  });
     location.reload();

};
function changeFrdStatus(id){
  var st = $('#fol'+id).text();
  console.log(id);
  $.get('/change-fr-status/' + id+ '/' + st, function(data){
    $('#fol'+id).text(data);
    console.log(data);
  });

};
//------------------------------------------
$(document).ready(function() {
var labl = '{!!$curUser->name!!}';
var scores = JSON.parse('{!!json_encode($chartData)!!}');
var usrLabels = JSON.parse('{!!json_encode($chartLabels)!!}');
console.log(scores);
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: usrLabels,
        datasets: [{
            label: labl,
            data: scores,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
});
//------------------------------------

</script>
@php($id = 1)


<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-7 sidenav pull-right" style="background-color: rgb(200,200,200); padding: 10px">

      <div class="row">
        <div class="col-sm-4">
          @if ($curUser->facebook_user_id != null)
          <img class="responsive" src="https://graph.facebook.com/{{ $curUser->facebook_user_id }}/picture?width=200&height=128" alt="HTML5 Icon" style="width: 200px;height:128px;">
          @endif
        </div>


        <div class="col-sm-4">
          {{$curUser->name}}
        </div>


        <div class="col-sm-4">
        @if ($isFBFriend == true)
        <i class="fa fa-facebook-square pull-right" style="color:blue;font-size:16px"> Friend</i>
        @endif
        @if ($curUser->id != Auth::user()->id)
          @if ($isFBFriend == false)
          <a id="fol{{$curUser->id}}" onclick="changeFrdStatus('{{$curUser->id}}')" class="btn btn-primary pull-right" type="button" name="button">
            @if($isFriend)
              Unfollow
            @else
              Follow
            @endif
          </a>
          @endif
        @endif
        </div>

      </div>
      <br>
      <br>
      <div class="row">
        <div class="col-sm-3 col-sm-offset-1" style="background-color:white; color:black">
          <h4>ACCOUNT SUMMARY</h4>

          <p>{{$curUser->invest_score}}</p>
          <p>{{$curUser->cash}}</p>
        </div>
        <div class="col-sm-3 col-sm-offset-1" style="background-color:white; color:black">
          <h4>PORTFOLIO</h4>
            @foreach($curUser->stocks as $stock)
              <p>{{$stock->stock_ticker}}<span style="color: purple"> Shares: {{$stock->shares}}</span></p>

            @endforeach

        </div>
        <div class="col-sm-3 col-sm-offset-1" style="background-color: white; color:black">
          <h4>SHORTED STOCKS</h4>
          @foreach($curUser->shorts as $short)
            <p>{{$short->stock_ticker}}<span style="color: purple"> Shares: {{$short->shares}}</span></p>

          @endforeach
        </div>
      </div>
      <canvas style="background-color: rgb(200,200,200)" id="myChart"></canvas>
    </div>
    <div class="col-sm-4" style="background-color: rgb(200,200,200); padding: 10px; margin-left: 10px">

        @if($fflag)
        <ul class="nav nav-tabs">
          <li><a href="/leaderboard">Everybody</a></li>
          <li class="active"><a href="/leaderboard/following">Following</a></li>
        </ul>
          <!--<li><a href="/leaderboard">Everybody</a></li>
          <li class="active"><a href="/leaderboard/following">Following</a></li>-->
        @else
        <ul class="nav nav-tabs">
          <li class="active"><a href="/leaderboard">Everybody</a></li>
          <li><a href="/leaderboard/following">Following</a></li>
        </ul>
          <!--<li class="active"><a href="/leaderboard">Everybody</a></li>
          <li><a href="/leaderboard/following">Following</a></li>-->
        @endif

      <br>
      <table id="example" class="display table" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Rank</th>
                <th>Score (USD)</th>

            </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>

            @if($fflag)
              <td><a href="/leaderboard/following/usr-prof/{{$user->pivot->friend_id}}" id="{{$user->pivot}}">{{$user->name}}</a></td>
            @else
              <td><a href="/leaderboard/usr-prof/{{$user->id}}" id="{{$user->id}}">{{$user->name}}</a></td>
            @endif

            <td></td>
            <td>{{$user->invest_score}}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
            <tr>
              <th>Name</th>
              <th>Rank</th>
              <th>Score (USD)</th>

            </tr>
        </tfoot>
    </table>

    </div>


  </div>
</div>
@else
<h4 class="text-center">Get some friends</h4>
@endif
@endsection
