


@extends('layouts.master')

@section('content')
<script>

</script>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-4 sidenav" style="background-color: white">
    <h3>Search Results</h3>
      <table id="example" class="display, table" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Closing Date</th>
                <th>Price</th>

            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Name</th>
              <th>Closing Date</th>
              <th>Price</th>

            </tr>
        </tfoot>
        <tbody>
          @foreach($category_closing_prices as $cmpny)
          <tr>
              <td><li><a href="#">{{ $cmpny[0] }}</a></li></td>
              <td>{{ $cmpny[1] }}</td>
              <td>{{ $cmpny[2] }}</td>

          </tr>
          @endforeach

          </tbody>
        </table>


    </div>

    <div class="col-sm-8">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"> </script>

  	<canvas style="background-color: rgb(200,200,200)" id="myChart"></canvas>
  	<script>
      	var ctx = document.getElementById('myChart').getContext('2d');
          var myl = ["January", "February", "March", "April", "May", "June", "July"];
          var myd = [0, 10, 5, 2, 20, 30, 45];
          var chart = new Chart(ctx, {
              // The type of chart we want to create
              type: 'line',

              // The data for our dataset
              data: {
                  labels: myl,
                  datasets: [{
                      label: "My First dataset",
                      backgroundColor: 'rgb(255, 99, 132)',
                      borderColor: 'rgb(255, 99, 132)',
                      data: myd,
                  }]
              },

              // Configuration options go here
              options: {}
          });
          var index;
          var nmyl = [];
          var nmyd = [];
          $(document).ready(function(){
            $('#getMessage').click(function(){
              $.get('getmsg', function(data){

                for (index = 0; index < data.length; ++index) {
                  $('#msg').append(data[index]);
                  nmyl.push((data[index])[1]);
                  nmyd.push((data[index])[2]);

                }
                console.log(nmyd);
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: nmyl,
                        datasets: [{
                            label: "My First dataset",
                            
                            borderColor: 'rgb(255, 99, 132)',
                            data: nmyd,
                        }]
                    },

                    // Configuration options go here
                    options: {}
                });
                //console.log(data);
              });
            });
          });
      </script>

      <div id="msg">This message will be replaced using Ajax.
         Click the button to replace the message.</div>

           <button id="getMessage">Replace Message</button>

    </div>
  </div>
</div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

  <div id = 'msg'>This message will be replaced using Ajax.
           Click the button to replace the message.</div>


            <button onClick="getMessage()"> Replace Message</button>

          <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

      <script>
         function getMessage(){
            $.ajax({
               type:'get',
               url:'/getmsg',

               success:function(data){
                  $("#msg").html(data.msg);
               }
            });
         }
</script>

@endsection
