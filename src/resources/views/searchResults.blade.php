


@extends('layouts.master')

@section('content')
<script>

</script>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-4 sidenav" style="background-color: rgb(200,200,200)">
    <h3>Search Results</h3>
      <table  id="example" class="display, table" width="100%" cellspacing="0">
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
              <td><li><a onClick="viewTicker('{{ $cmpny[0] }}')" id="'{{ $cmpny[0] }}'" href="#">{{ $cmpny[0] }}</a></li></td>
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
          var myl = [];// ["January", "February", "March", "April", "May", "June", "July"];
          var myd = [];//[0, 10, 5, 2, 20, 30, 45];
          var ds = [{
              label: "My First dataset",
              backgroundColor: 'rgb(255, 99, 132)',
              borderColor: 'rgb(255, 99, 132)',
              data: myd,
          }];
          var chartData =  {
              labels: myl,
              datasets: ds
          };
          var chart = new Chart(ctx, {
              // The type of chart we want to create
              type: 'line',

              // The data for our dataset
              data: chartData,

              // Configuration options go here
              options: {}
          });

          var index;
          var nmyl = [];
          var nmyd = [];
          var url = 'getmsg/';
          var flag = 0;

          function viewTicker(ticker){

            $.get('getmsg/' + ticker, function(data){

            if(flag != 0){
              nmyd=[];
              for (index = 0; index < data.length; ++index) {
                nmyd.push((data[index])[2]);

              }
              var cmp = {
                label: ticker, borderColor: 'rgb(0,0,0)', data: nmyd,
              };
              ds.push(cmp);
              flag= flag+1;
              chart.update();

            }else {

              flag= flag+1;
                for (index = 0; index < data.length; ++index) {
                  //$('#msg').append($('#msg').text());
                  nmyl.push((data[index])[1]);
                  nmyd.push((data[index])[2]);

                }
                chartData.labels = nmyl;
                var cmp = {
                  label: ticker, borderColor: 'rgb(0,0,0)', data: nmyd,
                };
                ds.push(cmp);

                myl = nmyl;
                myd =nmyd;


              chart.update();
              }
              });
      };
      </script>

      <div id="msg">This message will be replaced using Ajax.
         Click the button to replace the message.</div>

           <button onClick="viewTicker()" id="getMessage">Replace Message</button>

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
