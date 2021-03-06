@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <a class="btn btn-sm btn-outline-secondary" href="/logbook/update?date={{ $previousDay }}">
          <i class="fa fa-backward" aria-hidden="true"></i> Previous
        </a>

        <h5>Update the logbook for <strong>{{ $timeslots->start()->toFormattedDateString() }}</strong></h5>

        <a class="btn btn-sm btn-outline-secondary{{ $nextDay ? '' : ' disabled' }}" {{ $nextDay ? 'href=/logbook/update?date=' . $nextDay : ''}}>
          Next <i class="fa fa-forward" aria-hidden="true"></i>
        </a>
      </div>

      {{-- Start main if clause --}}
      @if($patronCategories->isEmpty())
      <div class="card-body">
        @include('layouts.partials.no-patron-categories')
      </div>
      @else
      <div class="card-body">
        <p class="card-text">
          <i class="fa fa-info-circle"></i>
          Insert or edit the visits count in the appropriate time range. If there were no visits for a given slot, simply leave the field empty. If you want to delete all visits in a field, replace the count with a 0 and save the form.
        </p>
      </div>

      <div class="ml-2 mr-2">
        {{-- Add a dismissible warning if there is already data stored for this day. --}}
        @if ($patronCategories->pluck('logbookEntries.*')->flatten()->all())
        <div class="row">
          <div class="col">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <strong>Be careful:</strong> You already saved some data for {{ $timeslots->start()->toFormattedDateString() }} in the logbook. Check the values in the table below.
            </div>
          </div>
        </div>
        @endif

        {{-- Errors. --}}
        @if (count($errors))
        <div class="row">
          <div class="col">
            <div class="alert alert-danger" role="alert">
              <strong>Error:</strong> {{ $errors->first() }}
            </div>
          </div>
        </div>
        @endif

        {{-- Form begins --}}
        <form method="POST" action="/logbook">
          {{ csrf_field() }}
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>Start Time</th>
                @foreach($patronCategories as $category)
                <th>
                  <abbr title="{{ $category->name }}">{{ $category->abbreviation or $category->name}}</abbr>
                </th>
                @endforeach
              </tr>
            </thead>

            {{-- Table body: iterates through timeslots and active categories. --}}
            <tbody>
              @foreach($timeslots as $timeslotNo => $timeslot)
              <tr>
                <th scope="row">
                  {{ $timeslot->start()->format('G:i') }}
                </th>

                {{-- Data inputs. --}}
                @foreach($patronCategories as $category)
                <td>
                  <input type="hidden" name="entry[{{ entry_id($timeslot, $category) }}][start_time]" value="{{ $timeslot->start()->toDateTimeString() }}">
                  <input type="hidden" name="entry[{{ entry_id($timeslot, $category) }}][end_time]" value="{{ $timeslot->end()->toDateTimeString() }}">
                  <input type="hidden" name="entry[{{ entry_id($timeslot, $category) }}][patron_category_id]" value="{{ $category->id }}">
                  <div class="form-group">
                    <input type="number" class="form-control form-control-sm
                    {{ $errors->has('entry.' . entry_id($timeslot, $category) . '.visits') ? ' is-invalid' : '' }}"
                    id="entry[{{ entry_id($timeslot, $category) }}][visits]"
                    name="entry[{{ entry_id($timeslot, $category) }}][visits]"
                    {{-- Retrieve existing values for the single fields or get the old request value --}}
                    value="{{ $formContent[$timeslotNo][$category->id] or old('entry.' . entry_id($timeslot, $category) . '.visits') }}">
                  </div>
                </td>
                @endforeach

              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Save the Log</button>
            <a href="{{ route('logbook.index') }}" class="btn btn-secondary">Discard Changes</a>
          </div>
        </form>
      </div>
      @endif
      {{-- End main if clause. --}}
    </div>
  </div>
</div>

@endsection
