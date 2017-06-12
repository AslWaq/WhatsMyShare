@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@php($id = 1)
<script>
$(document).ready(function() {
    $('#example').DataTable( {

    } );
} );
function usrProf(id){
  var i;
  console.log(id);
  $.get('usr-prof/' + id, function(data){
    console.log(data.name);
    
      $('#port').text('portf');
  });
  //   location.reload();

};
</script>
@php($id = 1)
<h3 class="text-center" style="color: white">LEADEROARDS</h3>
<br>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-7 sidenav pull-right" style="background-color: rgb(200,200,200); padding: 10px; margin-right: 10px">

      <div class="row">
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
           <img class="responsive" src="https://www.w3schools.com/images/w3schools_green.jpg" alt="HTML5 Icon" style="width: 200px;height:128px;">
        </div>
      </div>
      <br>
      <br>
      <div class="row">
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
          <h4>ACCOUNT SUMMARY</h4>
          <p>Lorem ipsum dolor sit amet..</p>
        </div>
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
          <h4>PORTFOLIO</h4>
            <div id="port">
                <p>Lorem ipsum dolor sit amet..</p>
            </div>
        </div>
        <div class="col-sm-3 col-sm-offset-1" style="background-color:black; color:white">
          <h4>SHORTED STOCKS</h4>
          <p>Lorem ipsum dolor sit amet..</p>
        </div>
      </div>
    </div>
    <div class="col-sm-4" style="background-color: rgb(200,200,200); padding: 10px; margin-left: 10px">
      <ul class="nav nav-tabs" style="background-color: white; margin:0px; padding: 3px;">
        <li class="active"><a data-toggle="tab" href="#home">Your Group</a></li>
        <li><a data-toggle="tab" href="#menu1">Everybody</a></li>
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
            <td><a onclick="usrProf('{{$user->id}}')"id="{{$user->id}}">{{$user->name}}</a></td>
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
