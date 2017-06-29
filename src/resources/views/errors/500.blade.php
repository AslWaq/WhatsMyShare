@extends('layouts.master')

@section('content')


<div class="text-center" style="margin-top: 15%">
  <h2>{{ $exception->getmessage() }}</h2>
</div>






@endsection
