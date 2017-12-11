<template>
  <canvas />
</template>


<script>
import {mapState, mapGetters} from 'vuex';
import Chart from 'chart.js';

export default {
    props: ['labels'],

    data() {
        return {
            chart: null,
        }
    },

    computed: {
        ...mapState(['updated']),
        ...mapGetters(['totalVisits']),
    },

    watch: {
        /**
         * Trigger a chart re-render each time two datasets are added.
         */
        updated() {
            if (this.updated % 2 === 0) {
                this.render();
            }
        }
    },

    mounted() {
        this.render();
    },

    methods: {
        /** 
         * Destroy any existing chart and create a new one.
         */
        render() {
            if (this.chart !== null) {
                this.chart.destroy();
            }

            return this.createChart();
        },

        /**
         * Create a new chart and store it in the chart data property.
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
