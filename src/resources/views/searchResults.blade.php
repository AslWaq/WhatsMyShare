@extends('layouts.master')

@section('content')
<canvas id="myChart"></canvas>
<script>
var data = {
  labels: ['jan','feb', 'mar','apr','may','jun','jul','aug'],
  datasets: [
    {
      data:[3,2,5,3,5,8,1,2]
    }
  ]
}
var ctx = document.querySelector('#myChart').getContext('2d');
new chart(ctx).Line(data);
</script>

@endsection
