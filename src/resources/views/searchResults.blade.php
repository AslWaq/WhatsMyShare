


@extends('layouts.master')

@section('content')

<script>
$(document).ready(function() {
    $('#example').DataTable( {
      ordering: false,
      "bSort": false
    } );
} );

</script>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-4 sidenav" style="background-color: rgb(200,200,200)">
    <h3>Search Results</h3>
    <h4>Searched for: {{$category}}</h4>
      <table  id="example" class="display, table" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Ticker</th>
                <th>Name</th>
                <th>Price (USD)</th>
                <th>View Chart</th>
                <th>Add to Cart</th>

            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Ticker</th>
              <th>Name</th>
              <th>Price</th>

            </tr>
        </tfoot>
        <tbody>
          @foreach($category_closing_prices as $cmpny)
          <tr>
              <td>{{ $cmpny[0] }}</td>
              <td><a target="_blank" href="{{ $cmpnyObj->get($cmpny[0])['link']}}">{{ $cmpnyObj->get($cmpny[0])['name']}}</a></td>
              <td>${{ number_format((float)$cmpny[2], 2, '.', '') }}</td>
              <td><button onClick="viewTicker('{{ $cmpny[0] }}')" class="fa fa-line-chart"></button></td>
              <td id="{{ $cmpny[0] }}"><button onClick="addToCart('{{ $cmpny[0] }}', '{{ $cmpnyObj->get($cmpny[0])['name']}}')" class="fa fa-cart-plus"></button></td>
          </tr>
          @endforeach

          </tbody>
        </table>


    </div>

    <div class="col-sm-6 col-md-8 push-md-4 push-sm-6">


  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"> </script>

    <canvas style="background-color: rgb(200,200,200); width: 100%" id="myChart"></canvas>
    <script>
          var ctx = document.getElementById('myChart').getContext('2d');
          var myl = [];// ["January", "February", "March", "April", "May", "June", "July"];
          var myd = [];//[0, 10, 5, 2, 20, 30, 45];
          var flag = 0;
          var ds = [
          ];
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
              options: {
                legend: {
                  onClick: function(event, legendItem) {
                    var index = legendItem.datasetIndex;
                    //var ci = this.chart;


                    ds.splice(index,1);
                    flag -= 1;
                    chart.update();
                  }
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero: true   // minimum value will be 0.
                    },
                    scaleLabel: {
                      display: true,
                      labelString: "Price (USD)",
                      fontSize: 15
                    }
                  }]
                }
              }
          });

          var index;
          var nmyl = [];
          var nmyd = [];
          var url = 'getmsg/';


          function viewTicker(ticker){

            $.get('getmsg/' + ticker, function(data){

            if(flag != 0){
              nmyd=[];
              for (index = 0; index < data.length; ++index) {
                nmyd.push((data[index])[1]);

              }
              var rgb = 'rgb(255, 0, 255)';
              if (flag == 1)
                rgb = 'rgb(255, 255, 0)';
                if (flag == 2)
                  rgb = 'rgb(255, 0, 0)';
                  if (flag == 3)
                    rgb = 'rgb(0, 255, 0)';
              var cmp = {
                label: ticker, borderColor: rgb, data: nmyd,
              };
              ds.push(cmp);
              flag= flag+1;
              chart.update();

            }else {

              flag= flag+1;
                for (index = 0; index < data.length; ++index) {
                  //$('#msg').append($('#msg').text());
                  nmyl.push((data[index])[0]);
                  nmyd.push((data[index])[1]);

                }
                chartData.labels = nmyl;
                var cmp = {
                  label: ticker, borderColor: 'rgb(0,0,0)', data: nmyd,
                };
                ds.push(cmp
                );

                myl = nmyl;
                myd =nmyd;


              chart.update();
              }
              });
      };
      function addToCart(ticker, name){

          var i = [ticker, name];
          var item = JSON.stringify(i);
              $.get('add-to-cart/' + item, function(data){
                console.log(data);
                $('#cart-content').text(data);
              });
              $('#'+ticker).text("Added");
      };
      </script>

    </div>
    <div class="col-sm-6 col-md-4 sidenav pull-md-8 pull-sm-6" style="background-color: rgb(200,200,200)">
    <h3>Search Results</h3>
    <div class="table-responsive">
      <table  id="example" class="display table" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Ticker</th>
                <th>Name</th>
                <th>Price (USD)</th>
                <th>View Chart</th>
                <th>Add to Cart</th>

            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>Ticker</th>
              <th>Name</th>
              <th>Price</th>

            </tr>
        </tfoot>
        <tbody>
          @foreach($category_closing_prices as $cmpny)
          <tr>
              <td>{{ $cmpny[0] }}</td>
              <td><a target="_blank" href="{{ $cmpnyObj->get($cmpny[0])['link']}}">{{ $cmpnyObj->get($cmpny[0])['name']}}</a></td>
              <td>${{ $cmpny[2] }}</td>
              <td><button onClick="viewTicker('{{ $cmpny[0] }}')" class="fa fa-line-chart"></button></td>
              <td id="{{ $cmpny[0] }}"><button onClick="addToCart('{{ $cmpny[0] }}', '{{ $cmpnyObj->get($cmpny[0])['name']}}')" class="fa fa-cart-plus"></button></td>
          </tr>
          @endforeach

          </tbody>
        </table>
      </div>

    </div>

  </div>
</div>



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
