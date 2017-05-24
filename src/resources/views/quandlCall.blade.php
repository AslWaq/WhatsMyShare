@extends('layouts.master')

@section('content')
<script>
function UserAction() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "https://www.quandl.com/api/v3/datasets/WIKI/FB/data.json", false);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send();
    var response = JSON.parse(xhttp.responseText);
    document.getElementById("myText").innerHTML = response;
}



$("button").on("click",function(){
      //console.log("hii");
      $.ajax({
        headers:{
           "key":"your key",
     "Accept":"application/json",//depends on your api
      "Content-type":"application/x-www-form-urlencoded"//depends on your api
        },   url:"https://www.quandl.com/api/v3/datasets/WIKI/FB/data.json",
        success:function(response){
          var r=JSON.parse(response);
          $("#main").html(r.base);
        }
      });
});

</script>
<button type="submit" onclick="UserAction()">Search</button>

<h1>"The json: " <span id="myText"></span></h1>
<!--JxDXY6jBDscX9-pYTiov-->
@endsection
