<template>
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card border-secondary">
      <h4 class="card-header">
        {{ category.name }}
      </h4>
      <div class="card-body text-center">
        <h2 class="display-2 text-center">
          {{ category.visits_count }}
        </h2>
        <div class="card-footer">
          <div>
              <div><button type="submit" class="btn btn-success btn-xl">Add User</button></div>
              <div><button type="submit" class="btn btn-xs btn-outline-danger">Subtract</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['category']
}
</script>
