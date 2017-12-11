<template>
  <table class="table">
    <thead>
        <tr>
            <th>Year</th>
            <th v-for="field in this.labels">{{ field }}</th>
        </tr>
        <tr v-for="dataset in this.totalVisits">
            <td>{{ dataset.label }}</td>
            <td v-for="value in dataset.data">{{ value }}</td>
        </tr>
    </thead>

  </table>
</template>

<script>
import {mapState, mapGetters} from 'vuex';


export default {
    computed: {
            ...mapState(['updated']),
            ...mapGetters(['totalVisits', 'labels']),
        }
}
</script>

