@extends('logbook.index')

@section('tab-content')
<div class="col">
    <h3>Year statistics</h3>

    <chart type="bar"
    label="# of Users in 2017"
    :labels="['January', 'February', 'March', 'April', 'May', 'June']"
    :values="[10, 42, 4, 5, 34, 6]"></chart>

    <chart type="bar"
    label="# of Users in 2017"
    :labels="['January', 'February', 'March', 'April', 'May', 'June']"
    :values="[10, 12, 42, 512, 324, 6]"></chart>

</div>
@endsection
