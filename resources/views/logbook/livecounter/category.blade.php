{{-- If the category is secondary it adds the classes collapse multi-collapse. --}}
<div class="col-lg-3 col-md-6 col-sm-6 mb-4{{ $category->is_primary ? '' : ' collapse multi-collapse' }}">
  <div class="card">
    <h4 class="card-header">
      {{ $category->name }}
    </h4>
    <div class="card-body text-center">
      <h2 class="display-2 text-center">
        {{ $category->logbookEntries->first()->visits or '0'}} {{-- If there is a logbook entry retrieve the visits count, otherwise 0. --}}
      </h2>
      <div class="card-footer">
        <div>
          <form method="POST" action="{{ route('livecounter.index') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $category->id }}">
            <button type="submit" class="btn btn-success btn-xl" name="operation" value="add">Add User</button> {{-- Add User button --}}
            <div>
              <button type="submit" class="btn btn-xs btn-outline-danger" name="operation" value="subtract">Subtract</button>{{-- Subtract button --}}
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
