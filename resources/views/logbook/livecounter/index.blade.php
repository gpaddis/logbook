@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">Live Counter Index</div>

      <div class="card-body">

        {{-- Start primary categories cards. --}}
        <div class="row justify-content-center">
          @foreach($primary_active_patron_categories as $category)
          <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
              <h4 class="card-header">
                {{ $category->name }}
              </h4>
              <div class="card-body text-center">
                <h2 class="display-2 text-center">
                  {{ $category->currentVisits() }}
                </h2>

                <div class="card-footer">
                  <div>
                    <form method="POST" action="{{ route('livecounter.index') }}">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $category->id }}">
                      {{-- Add User button --}}
                      <button type="submit" class="btn btn-success btn-xl mb-2" name="operation" value="add">Add User</button>
                      {{-- Subtract button --}}
                      <button type="submit" class="btn btn-sm btn-outline-danger" name="operation" value="subtract">Subtract</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          {{-- End primary categories cards. --}}
        </div>

        @if($secondary_active_patron_categories->count())
        <div class="col text-center">
          <p>
            <a data-toggle="collapse" href="#secondaryCategories" aria-expanded="false" aria-controls="secondaryCategories">
              Toggle secondary categories...
            </a>
          </p>
        </div>

        {{-- Start secondary categories cards. --}}
        <div class="collapse" id="secondaryCategories">
          <div class="row justify-content-center">
            {{-- Card. --}}
            @foreach($secondary_active_patron_categories as $category)
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
              <div class="card">
                <h4 class="card-header">
                  {{ $category->name }}
                </h4>
                <div class="card-body text-center">
                  <h2 class="display-2 text-center">
                    {{ $category->currentVisits() }}
                  </h2>

                  <div class="card-footer">
                    <div>
                      <form method="POST" action="{{ route('livecounter.index') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        {{-- Add User button --}}
                        <button type="submit" class="btn btn-success btn-xl mb-2" name="operation" value="add">Add User</button>
                        {{-- Subtract button --}}
                        <button type="submit" class="btn btn-sm btn-outline-danger" name="operation" value="subtract">Subtract</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
            {{-- End card. --}}
          </div>
        </div>
        {{-- End secondary categories cards. --}}
        @endif

      </div>
    </div>
  </div>
</div>

@endsection
