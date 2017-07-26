@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">
					<h4>Visits Log</h4>
				</div>
				<div class="panel-body">
					<h4>Insert or update the visits log for {{ $timeslots[0]['start']->toDateString() }}</h4>
				</div>

				{{-- Form begins --}}
				<form method="POST" action="/logbook">

					{{ csrf_field() }}

					<table class="table">
						<tr>
							<th>Timeslot / Category</th>
							@foreach($categories as $category)
							<th><abbr title="{{ $category->name }}">{{ $category->abbreviation }}</abbr></th>
							@endforeach
						</tr>

						{{-- Table body: iterates through timeslots and active categories --}}
						@foreach($timeslots as $timeslot)
						<tr>
							<td class="col-md-2">
								<p>
									From: {{ $timeslot['start']->toTimeString() }}
									To: {{ $timeslot['end']->toTimeString() }}
								</p>
							</td>

							{{-- Data inputs --}}
							@foreach($categories as $category)
							<td class="col-md-1">
								<input type="hidden" name="entry[{{ $timeslot['start']->timestamp . $category->id }}][start_time]" value="{{ $timeslot['start']->toDateTimeString() }}">
								<input type="hidden" name="entry[{{ $timeslot['start']->timestamp . $category->id }}][end_time]" value="{{ $timeslot['end']->toDateTimeString() }}">
								<input type="hidden" name="entry[{{ $timeslot['start']->timestamp . $category->id }}][patron_category_id]" value="{{ $category->id }}">

								<div class="form-group">
									<input type="number" class="form-control" id="entry[{{ $timeslot['start']->timestamp . $category->id }}][count]" name="entry[{{ $timeslot['start']->timestamp . $category->id }}][count]">
								</div>
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
			</form>
			
			{{-- TODO: fix the error output. Check this: https://ericlbarnes.com/2015/04/04/laravel-array-validation/ --}}
			@if($errors)
			<ul>
				@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
			@endif
		</div>

	</div>
</div>
</div>

@endsection
