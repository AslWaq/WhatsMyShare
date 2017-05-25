@extends('layouts.master')

@section('content')


  <div class="container">
      <div class="row">
          <div class="col-md-8 col-md-offset-2">



                      <form class="form-horizontal" method="post" action="/search-cat">
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Form Name</legend>
                        {{ csrf_field() }}
                        <!-- Appended Input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="appendedtext">Search by Category</label>
                          <div class="col-md-4">
                            <div class="input-group">
                              <select id="appendedtext" name="categoryChoice" class="form-control">
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

                              <span class="input-group-addon"><button type="submit">Submit</button></span>
                            </div>
                            <p class="help-block">help</p>
                          </div>
                        </div>
                        </fieldset>
                        </form>

                        <for method="post" action="/search-name">
                          {{ csrf_field() }}

                          <!-- Appended Input-->
                          <div class="form-group">
                            <label class="col-md-4 control-label" for="appendedtext">Search by company name:</label>
                            <div class="col-md-4">
                              <div class="input-group">
                                <input id="appendedtext" name="textSearch" class="form-control" placeholder="placeholder" type="text">

                                <span class="input-group-addon"><button type="submit">Submit</button></span>
                              </div>
                              <p class="help-block">help</p>
                            </div>
                          </div>

                          </form>







          </div>

      </div>

  </div>

@endsection
