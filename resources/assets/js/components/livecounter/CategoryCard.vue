<template>
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card border-secondary">
      <h4 class="card-header">
        {{ name }}
      </h4>
      <div class="card-body text-center">
        <h2 class="display-2 text-center">
          {{ visits }}
        </h2>
        <div class="card-footer">
          <div>
              <div><button @click="add" class="btn btn-success btn-xl">Add User</button></div>
              <div><button type="submit" class="btn btn-xs btn-outline-danger">Subtract</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['categoryId', 'name', 'visits'],

  methods: {
    add() {
      axios.post('/logbook/livecounter/add', { patron_category_id: this.categoryId});

      this.$emit('updated');
    }
  }
}
</script>
