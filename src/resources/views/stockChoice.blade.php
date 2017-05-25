@extends('layouts.master')

@section('content')


  <div class="container">
      <div class="row">
          <div class="col-md-6 col-md-offset-3">




  <br>
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
      <input name="textSearch" type="email" class="form-control" placeholder="Company Name" required>
      <div class="input-group-btn">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </form>
  <br>

</div>


</div>

</div>

</div>

@endsection
