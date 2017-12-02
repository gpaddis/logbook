<template>
  <canvas />
</template>


<script>
import {mapGetters} from 'vuex';
import Chart from 'chart.js';

export default {    
    data() {
        return {
            chart: null,
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            colors: ['rgba(249, 178, 72, 0.5)', 'rgba(252, 58, 82, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(204, 101, 254, 0.5)']
        }
    },

    computed: {
        ...mapGetters(['totalVisits']),
    },

    mounted() {
        this.createChart();
    },

    methods: {
        render() {
            this.chart.destroy();
            return this.createChart();
        },

        /**
         * Create a new chart and store it in the chart property.
         */
        createChart() {
            this.chart = new Chart(this.$el, {
                type: 'bar',
                data: {
                    labels: this.labels,
                    datasets: this.totalVisits
                }
            });
        }
    }
}
</script>
