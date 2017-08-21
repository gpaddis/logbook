@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">Live Counter Index</div>
      <div class="card-body">
        <div class="row justify-content-center">
        {{-- Start categories cards. --}}
          @forelse($patron_categories as $category)
          {{-- If the category is secondary it adds the classes collapse multi-collapse. --}}
          <div class="col-lg-3 col-md-6 col-sm-6 mb-4{{ $category->is_primary ? '' : ' collapse multi-collapse' }}">
            <div class="card">
              <h4 class="card-header">
                {{ $category->name }}
              </h4>
              <div class="card-body text-center">
                <h2 class="display-2 text-center">
                  {{-- If there is a logbook entry retrieve the visits count, otherwise 0. --}}
                  {{ $category->logbookEntries->first()->visits_count or '0'}}
                </h2>

                <div class="card-footer">
                  <div>
                    <form method="POST" action="{{ route('livecounter.index') }}">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $category->id }}">
                      {{-- Add User button --}}
                      <button type="submit" class="btn btn-success btn-xl" name="operation" value="add">Add User</button>
                      {{-- Subtract button --}}
                      <div>
                        <button type="submit" class="btn btn-xs btn-outline-danger" name="operation" value="subtract">Subtract</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="col">
            @include('layouts.partials.no-patron-categories')
          </div>
          @endforelse
        </div>
        {{-- End categories cards. --}}

        {{-- Toggle secondary categories. --}}
        @if($patron_categories->where('is_primary', false)->count())
        <div class="col text-center">
          <p>
            <a href="#" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false">
              Toggle secondary categories...
            </a>
          </p>
        </div>
        @endif
      </div>
    </div>
  </div>

  @endsection
