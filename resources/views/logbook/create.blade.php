@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">
					<h4>Update the logbook for {{ $timeslots[0]->start()->toFormattedDateString() }}</h4>
				</div>

				{{-- Start main if clause --}}
				@if($active_patron_categories->count() == 0)
					<div class="panel-body">
                        <p>It looks like there are no active patron categories yet. Ask the admin to <a href="#">create some</a>!</p>
                    </div>
				@else

				<div class="panel-body">
					<p>Insert the user count for a specific time range into the appropriate field. If no users of a specific category visited the library during a given time range, simply leave the according fields empty.</p>
				</div>

				{{-- Form begins --}}
				<form method="POST" action="/logbook">

					{{ csrf_field() }}

					<table class="table table-hover">
						<tr>
							<th>Time Range</th>
							@foreach($active_patron_categories as $category)
							<th><abbr title="{{ $category->name }}">{{ $category->abbreviation }}</abbr></th>
							@endforeach
						</tr>

						{{-- Table body: iterates through timeslots and active categories --}}
						@foreach($timeslots as $timeslot)
						<tr>
							<td class="col-md-2">
								<p>
									From {{ $timeslot->start()->format('G:i') }}
									to {{ $timeslot->end()->format('G:i') }}
								</p>
							</td>

							{{-- Data inputs --}}
							@foreach($active_patron_categories as $category)
							<td>
								<input type="hidden" name="entry[{{ App\Logbook\Entry::identifier($timeslot, $category) }}][start_time]" value="{{ $timeslot->start()->toDateTimeString() }}">

								<input type="hidden" name="entry[{{ App\Logbook\Entry::identifier($timeslot, $category) }}][end_time]" value="{{ $timeslot->end()->toDateTimeString() }}">

								<input type="hidden" name="entry[{{ App\Logbook\Entry::identifier($timeslot, $category) }}][patron_category_id]" value="{{ $category->id }}">

								<div class="form-group{{ 
									$errors->has('entry.' . App\Logbook\Entry::identifier($timeslot, $category) . '.count') ? ' has-error' : '' }}">
									<input type="number" class="form-control input-count" id="entry[{{ App\Logbook\Entry::identifier($timeslot, $category) }}][count]" name="entry[{{ App\Logbook\Entry::identifier($timeslot, $category) }}][count]" value="{{ old('entry.' . App\Logbook\Entry::identifier($timeslot, $category) . '.count') }}">
								</div>
							</td>
							@endforeach

						</tr>
						@endforeach

					</table>

					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary">Save the Log</button>
						<a href="#" class="btn btn-default">Clear the Form</a>
					</div>
					
					{{-- Errors --}}
					@if (count($errors))
					<ul class="alert alert-danger">
						<li>{{ $errors->first() }}</li>
					</ul>
					@endif
				</form>

				@endif
				{{-- End main if clause --}}

			</div>
		</div>
	</div>
</div>

@endsection
