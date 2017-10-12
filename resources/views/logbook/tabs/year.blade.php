@extends('logbook.index')

@section('tab-content')
<div class="col">

    <div class="row">
        <div class="col-sm col-lg-2 mb-2">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h1 class="display-4">{{ $days or 0 }}</h1>
                    <p>Opening days</p>
                </div>
            </div>
        </div>

        <div class="col-sm col-lg-2 mb-2">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h1 class="display-4">{{ $visits->sum() ?: 0 }}</h1>
                    <p>Visits</p>
                </div>
            </div>
        </div>

        <div class="col-sm col-lg-2 mb-2">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h1 class="display-4">{{ number_format(($visits->sum() / $days), 1) }}</h1>
                    <p>Visits / day</p>
                </div>
            </div>
        </div>

        <div class="col-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <h3>User statistics: 2017</h3>

                    <p class="card-text">
                        Choose a year from the dropdown to display the relative statistics:
                    </p>

                    <select class="custom-select">
                      <option selected>Select a year</option>
                      @forelse($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                      @empty
                        <option value="none">No data available</option>
                      @endforelse
                  </select>
              </div>
          </div>
      </div>
  </div>

  <hr>

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
        <h1>Monthly visits</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad repudiandae optio enim commodi pariatur maxime dignissimos, voluptate! Expedita eum distinctio quo quae illo, necessitatibus sapiente, minima praesentium reprehenderit magnam accusamus.</p>
    </div>
</div>

<hr>

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
