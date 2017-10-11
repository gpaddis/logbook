@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">Logbook Index</div>

      <div class="card-body">
        <!-- Tabs start -->
        <ul class="nav nav-tabs mb-2">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logbook.index') }}">Overview</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logbook.day') }}">Day</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Month</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logbook.year') }}">Year</a>
          </li>
        </ul>
        <!-- Tabs end -->

        <div class="row justify-content-left">
          @yield('tab-content')
        </div>
      </div>
    </div>
  </div>

  @yield('tab-sidebar')
</div>
@endsection

@push('scripts')
<script>
/**
 * Activate the tab when URI == href attribute.
 */
 $(document).ready(function(){
  let full_path = location.href.split("?")[0];
  $(".nav li a").each(function(){
    let $this = $(this);
    if($this.prop("href").split("?")[0] == full_path) {
      $this.addClass("active");
      return false;
    }
  });
});
</script>
@endpush
