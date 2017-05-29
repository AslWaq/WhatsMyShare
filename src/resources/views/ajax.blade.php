<html>
   <head>
      <title>Ajax Example</title>

      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"> </script>

   </head>

   <body>


   <script>
   var index;
   $(document).ready(function(){
     $('#getMessage').click(function(){
       $.get('getmsg', function(data){

         for (index = 0; index < data.length; ++index) {
           $('#msg').append(data[index]);
           console.log(data[index]);
         }
         //console.log(data);
       });
     });
   });
   //------------------------------------

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

        //---------------------------------------------------------------------------------------------------------
   </script>

   <canvas id="myChart"></canvas>
      <div id="msg">This message will be replaced using Ajax.
         Click the button to replace the message.</div>

           <button id="getMessage">Replace Message</button>


   </body>

</html>
