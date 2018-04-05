<template>
  <div class="card">
    <div class="card-body">
      <h4>Comparison</h4>

      <table class="table table-sm">
        <thead>
          <th>Period</th>
          <th v-for="dataset in this.totalVisits">
            {{ dataset.label }}
          </th>
          <th>
            <i class="fa fa-line-chart" aria-hidden="true" title="Increase / decrease percentage"></i>
          </th>
        </thead>

        <tbody v-if="ready">
          <tr v-for="(useless, index) in serie1" :key="index">
            <td>{{ labels[index] }}</td>
            <td>{{ serie1[index] }}</td>
            <td>{{ serie2[index] }}</td>
            <td>
              <div v-if="seriesVariation(index)"
                :class="seriesVariation(index) >= 0 ? 'text-success' : 'text-danger'">
                {{ seriesVariation(index) }}%
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import {mapState, mapGetters} from 'vuex';


export default {
    computed: {
        ...mapState(['updated']),
        ...mapGetters(['totalVisits', 'labels']),

        /**
         * Evaluate to true if both datasets are loaded.
         */
        ready() {
            return this.totalVisits.hasOwnProperty(0) && this.totalVisits.hasOwnProperty(1);
        },

        /**
         * The first dataset.
         */
        serie1() {
            return this.totalVisits[0].data;
        },

        /**
         * The second dataset.
         */
        serie2() {
            return this.totalVisits[1].data;
        }
    },

    methods: {
        /**
         * Calculate the percentage variation between the values provided.
         */
        calculateVariation(first, second) {
            if (first && second) {
                let variation = second - first;

                return (Math.round((variation / first) * 100));
            }
        },

        /**
         * Return the percentage variation between elements of the two datasets
         * with the same index.
         */
        seriesVariation(index) {
            return this.calculateVariation(this.serie1[index], this.serie2[index]);
        }
    }
}
</script>
