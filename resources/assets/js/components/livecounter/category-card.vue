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
              <div><button @click="subtract" class="btn btn-xs btn-outline-danger">Subtract</button></div>
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
      this.$emit('add', this.categoryId);
    },

    subtract() {
      this.$emit('subtract', this.categoryId);
    }
  }
}
</script>
