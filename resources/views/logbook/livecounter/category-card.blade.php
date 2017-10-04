{{-- If the category is secondary it adds the classes collapse multi-collapse. --}}
<div class="col-lg-3 col-md-6 col-sm-6 mb-4{{ $category->is_primary ? '' : ' collapse multi-collapse' }}">
  <div class="card border-secondary">
    <h4 class="card-header">
      {{ $category->name }}
    </h4>
    <div class="card-body text-center">
      <h2 class="display-2 text-center">{{ $category->logbookEntries->count()}}</h2>
      <div class="card-footer">
        <div>
          <form method="POST" action="{{ route('livecounter.add') }}">
            {{ csrf_field() }}
            <input type="hidden" name="patron_category_id" value="{{ $category->id }}">
            <button type="submit" class="btn btn-success btn-xl">Add User</button> {{-- Add User button --}}
          </form>

          <form method="POST" action="{{ route('livecounter.subtract') }}">
            {{ csrf_field() }}
            <input type="hidden" name="patron_category_id" value="{{ $category->id }}">
            <button type="submit" class="btn btn-xs btn-outline-danger">Subtract</button> {{-- Subtract button --}}
          </form>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
