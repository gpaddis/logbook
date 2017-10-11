@extends('logbook.index')

@section('tab-content')
<div class="col">
    <h3>Year statistics</h3>

    <div class="row">
        <div class="col-md-8">
            <chart type="bar"
            label="# of Users in 2017"
            :keys="{{ $visits->keys() }}"
            :values="{{ $visits->values() }}"
            background-color="rgba(255, 12, 2, 0.2)"
            border-color="rgba(254, 43, 132, 1)"></chart>
        </div>
        <div class="col">
            <h1>Total number of users</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad repudiandae optio enim commodi pariatur maxime dignissimos, voluptate! Expedita eum distinctio quo quae illo, necessitatibus sapiente, minima praesentium reprehenderit magnam accusamus.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <chart type="line"
            label="Whatever you want to measure"
            :keys="{{ $visits->keys() }}"
            :values="{{ $visits->values() }}"
            background-color="rgba(255, 99, 132, 0.2)"
            border-color="rgba(255, 99, 132, 1)"></chart>
        </div>
        <div class="col">
            <h1>Something else</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad repudiandae optio enim commodi pariatur maxime dignissimos, voluptate! Expedita eum distinctio quo quae illo, necessitatibus sapiente, minima praesentium reprehenderit magnam accusamus.</p>
        </div>
    </div>

</div>
@endsection
