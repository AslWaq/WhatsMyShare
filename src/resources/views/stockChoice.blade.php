@extends('layouts.master')

@section('content')
<link rel ="stylesheet" href="jquery-ui.min.css">
<script src="jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
    var suggestions = {!!$results!!};
    // $("#textSearch").keyup(function(){
    //   var keyword = $('#textSearch').val();
    //   //console.log(keyword);
    //   $.get('autocomplete/' + keyword, function(data){
    //       //console.log(data);
    //       console.log(JSON.parse(data));
    //
    //       });


          $( "#textSearch" ).autocomplete({
            source: suggestions
          });

});


</script>
  <div class="container">
      <div class="row">
          <div class="col-md-6 col-md-offset-3">




  <br>
  <h3 class="text-center" style="color: #07889B">Stock Search</h3>
  <form method="post" action="/search-cat">
    {{ csrf_field() }}
    <label style="color: white">Search by Category</label>
    <div class="input-group">

      <select  name="categoryChoice" class="form-control" required>
        <option value="Industrials">Industrials</option>
        <option value="Health Care">Health Care</option>
        <option value="IT">IT</option>
        <option value="Consumer">Consumer</option>
        <option value="Utilities">Utilities</option>
        <option value="Financials">Financials</option>
        <option value="Materials">Materials</option>
        <option value="Real Estate">Real Estate</option>
        <option value="Telecommunications">Telecommunications</option>
        <option value="Energy">Energy</option>
      </select>
      <div class="input-group-btn">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>
  <br>
  <br>
  <form method="post" action="/search-name">
    {{ csrf_field() }}
    <label style="color: white">Search by Company Name</label>
    <div class="input-group">
      <input id="textSearch" name="textSearch" type="text" class="form-control" required>
      <div class="input-group-btn">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>
  @if (Session::has('nameSearchError'))
    <div class="alert alert-danger">
      {{Session::pull('nameSearchError')}}
    </div>
  @endif
  <br>

</div>


</div>

</div>

</div>

@endsection
