@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">

      <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">
        <h4>Visits Log</h4>
        </div>
        <div class="panel-body">
        <p>Insert or update the visits log for the day ...</p>
        </div>
        
        {{-- Form begins --}}
        <form method="POST" action="/visits">
          
          {{ csrf_field() }}
          
          <table class="table">
            <tr>
              <th>Timeslot / Category</th>
              @foreach($categories as $category)
              <th>{{ $category->name }}</th>
              @endforeach
            </tr>

            {{-- Table body: iterates through timeslots and active categories --}}
            <tr>
              <td class="col-md-2">
                <p>Timeslot 1</p>
              </td>
              
              {{-- Data inputs --}}
              @foreach($categories as $category)
              <td class="col-md-1">
                <div class="form-group">
                  <input type="text" class="form-control" id="count['timeslot'][{{ $category->id }}]" name="count['timeslot'][{{ $category->id }}]">
                </div>
              </td>
              @endforeach

            </tr>
          </table>

          <div class="form-group text-center">
            <button type="submit" class="btn btn-default">Submit</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection
