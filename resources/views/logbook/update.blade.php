@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">Update the logbook for {{ $timeslots[0]->start()->toFormattedDateString() }}</div>

      {{-- Start main if clause --}}
      @if($patron_categories->isEmpty())
      <div class="card-body">
        @include('layouts.partials.no-patron-categories')
      </div>
      @else
      <div class="card-body">
        <p class="card-text">
          Insert the user count for a specific time range into the appropriate field. If no users of a specific category visited the library during a given time range, simply leave the according fields empty. If you want to delete an existing record, write a 0 in the according field.
        </p>
      </div>

      <div class="ml-2 mr-2">
        {{-- Add a dismissible warning if there is already data stored for this day. --}}
        @if ($patron_categories->pluck('logbookEntries.*')->flatten()->all())
        <div class="row">
          <div class="col">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <strong>Be careful:</strong> You already saved some data for {{ $timeslots[0]->start()->toFormattedDateString() }} in the logbook. Check the values in the table below.
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
                <th>Time Range</th>
                @foreach($patron_categories as $category)
                <th>
                  <abbr title="{{ $category->name }}">{{ $category->abbreviation }}</abbr>
                </th>
                @endforeach
              </tr>
            </thead>

            {{-- Table body: iterates through timeslots and active categories. --}}
            <tbody>
              @foreach($timeslots as $timeslot)
              <tr>
                <th scope="row">
                  From {{ $timeslot->start()->format('G:i') }} to {{ $timeslot->end()->format('G:i') }}
                </th>

                {{-- Data inputs. --}}
                @foreach($patron_categories as $category)
                <td>
                  <input type="hidden" name="entry[{{ entry_id($timeslot, $category) }}][start_time]" value="{{ $timeslot->start()->toDateTimeString() }}">

                  <input type="hidden" name="entry[{{ entry_id($timeslot, $category) }}][end_time]" value="{{ $timeslot->end()->toDateTimeString() }}">

                  <input type="hidden" name="entry[{{ entry_id($timeslot, $category) }}][patron_category_id]" value="{{ $category->id }}">

                  <div class="form-group">
                    <input type="number" class="form-control form-control-sm
                    {{ $errors->has('entry.' . entry_id($timeslot, $category) . '.visits_count') ? ' is-invalid' : '' }}"
                    id="entry[{{ entry_id($timeslot, $category) }}][visits_count]"
                    name="entry[{{ entry_id($timeslot, $category) }}][visits_count]"
                    {{-- Retrieve existing values for the single fields or get the old request value --}}
                    @if($category->logbookEntries->where('start_time', $timeslot->start())->isNotEmpty())
                    value="{{ $category->logbookEntries->where('start_time', $timeslot->start())->first()->visits_count }}"
                    @else
                    value="{{ old('entry.' . entry_id($timeslot, $category) . '.visits_count') }}"
                    @endif>
                  </div>
                </td>
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Save the Log</button>
            <a href="#" class="btn btn-secondary">Clear the Form</a>
          </div>
        </form>

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
      </div>
      @endif
      {{-- End main if clause. --}}
    </div>
  </div>
</div>

@endsection
