@extends('layouts.master')

@section('content')
<h3 class="text-center" style="color: #07889B">Leaderboards</h3>
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
  var status = st.trim();
  console.log(status);
  $.get('/change-fr-status/' + id+ '/' + status, function(data){
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
        legend: {
          display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                scaleLabel: {
                    display: true,
                    labelString: "Invest Score (USD)",
                    fontSize: 12
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
    <div class="col-sm-7 sidenav" style="float: right; background-color: #D9D9D9; padding: 10px">

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
        <!--<div class="col-sm-3 col-sm-offset-1" style="background-color:white; color:black">
          <h4>ACCOUNT SUMMARY</h4>

          <p>{{$curUser->invest_score}}</p>
          <p>{{$curUser->cash}}</p>
        </div><-->
        <div class="col-sm-4">
          <div class="panel panel-primary">
            <div class="panel-heading">My Account</div>
            <div class="panel-body">
              <h4 >My Account Summary</h4>
              <p><span>Liquid Cash Balance: </span><span style="color:green">${{$curUser->cash}}</span></p>
              <p><span>Invest Score: </span><span style="color:green">${{$curUser->invest_score}}</span></p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="panel panel-primary">
            <div class="panel-heading">My Portfolio</div>
            <div class="panel-body text-center">
              @php ($stocks = $curUser->stocks)
              @if($stocks->count() > 0)
                @if ($stocks->count() >= 3)
                  @php ($stockRange = 3)
                @else
                  @php ($stockRange = $shorts->count())
                @endif
                @for ($i=0; $i<$stockRange; $i++)
                  <p>{{$stocks[$i]->stock_ticker}}<span style="color: purple"> Shares: {{$stocks[$i]->shares}}</span></p>
                @endfor
              @endif
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="panel panel-primary">
            <div class="panel-heading">Shorted Stocks</div>
            <div class="panel-body text-center">
              @php ($shorts = $curUser->shorts)
              @if($shorts->count() > 0)
                @if ($shorts->count() >= 3)
                  @php ($shortRange = 3)
                @else
                  @php ($shortRange = $shorts->count())
                @endif
                @for ($i=0; $i<$shortRange; $i++)
                  <p>{{$shorts[$i]->stock_ticker}}<span style="color: purple"> Shares: {{$shorts[$i]->shares}}</span></p>
                @endfor
              @endif
            </div>
          </div>
        </div>
      </div>
      <canvas style="background-color: #D9D9D9" id="myChart"></canvas>
    </div>
    <div class="col-sm-4" style="float:left; background-color: #D9D9D9; padding: 10px; margin-left: 10px">

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
          @php ($rankIterator = 1)
          @foreach($users as $user)
          <tr>

            @if($fflag)
              <td><a href="/leaderboard/following/usr-prof/{{$user->pivot->friend_id}}" id="{{$user->pivot}}">{{$user->name}}</a></td>
            @else
              <td><a href="/leaderboard/usr-prof/{{$user->id}}" id="{{$user->id}}">{{$user->name}}</a></td>
            @endif

            <td>{{$rankIterator}}</td>
            <td>{{$user->invest_score}}</td>
          </tr>
          @php ($rankIterator++)
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
<h4 class="text-center col-md-8 col-md-offset-2">You are currently following no users. Please go back to the 'Everybody' tab in the Leaderboards
section and click on the 'Follow' button on the user profiles to the right to start following users. If you would like to
remove a user from your 'Following' list simply click the Unfollow button on their corresponding profile.
</h4>
@endif
@endsection
