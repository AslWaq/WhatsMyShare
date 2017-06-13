@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@php($id = 1)
<script>
$(document).ready(function() {
    $('#example').DataTable( {

    } );
} );
// function usrProf(id){
//   var i;
//   console.log(id);
//   $.get('usr-prof/' + id, function(data){
//
//     console.log(data[1].length);
//     for (i=0; i<data[1].length; i++){
//       $('#port').append(data[1][i].stock_ticker);
//     }
//   });
//   //   location.reload();
//
// };
</script>
@php($id = 1)
<?php
  $isFollowed = false;
  $friends = Auth::user()->friends;
  if ($friends == null){
    $isFollowed = false;
  }else{
    foreach ($friends as $friend){
      if ($friend->id = $curUser->id){
        $isFollowed = true;
      }
    }
  }
?>
<h3 class="text-center" style="color: white">LEADERBOARDS</h3>
<br>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-7 sidenav pull-right" style="background-color: rgb(200,200,200); padding: 10px; margin-right: 10px">

      <div class="row">
        @if ($curUser->facebook_user_id != null)
        <div class="col-sm-4">
          <img class="responsive" src="https://graph.facebook.com/{{ $curUser->facebook_user_id }}/picture?width=200&height=128" alt="HTML5 Icon" style="width: 200px;height:128px;">
        </div>
        @endif

        <div class="col-sm-4">
          {{$curUser->name}}
        </div>
        @if ($curUser->id != Auth::user()->id)
        <div class="col-sm-4">
          <button class="btn btn-primary pull-right" type="button" name="button">
            @if($isFollowed == true)
              Follow
            @else
              Unfollow
            @endif
          </button>
        </div>
        @endif
      </div>
      <br>
      <br>
      <div class="row">
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
          <h4>ACCOUNT SUMMARY</h4>
          <p>{{$curUser->invest_score}}</p>
          <p>{{$curUser->cash}}</p>
        </div>
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
          <h4>PORTFOLIO</h4>
            @foreach($curUser->stocks as $stock)
              <p>{{$stock->stock_ticker}}<span style="color: purple"> Shares: {{$stock->shares}}</span></p>

            @endforeach

        </div>
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
          <h4>SHORTED STOCKS</h4>
          @foreach($curUser->shorts as $short)
            <p>{{$short->stock_ticker}}<span style="color: purple"> Shares: {{$short->shares}}</span></p>

          @endforeach
        </div>
      </div>
    </div>
    <div class="col-sm-4" style="background-color: rgb(200,200,200); padding: 10px; margin-left: 10px">
      <ul class="nav nav-tabs" style="background-color: white; margin:0px; padding: 3px;">
        <li class="active"><a href="/leaderboard">Everybody</a></li>
        <li><a href="/leaderboard/following">Following</a></li>
      </ul>
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
            <td><a href="/usr-prof/{{$user->id}}" "id="{{$user->id}}">{{$user->name}}</a></td>
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
@endsection
