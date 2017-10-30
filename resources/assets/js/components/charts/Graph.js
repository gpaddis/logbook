import Vue from 'vue';
import Chart from 'chart.js';

/**
 * This is the master level chart to be subclassed by all specific charts.
 */
 export default Vue.extend({
    template: `<canvas></canvas>`,

    data() {
        return {
            colors: ['rgba(249, 178, 72, 0.5)', 'rgba(252, 58, 82, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(204, 101, 254, 0.5)'],
        }
    },

    mounted() {
        var myChart = new Chart(this.$el, {
            type: this.type,
            data: {
                labels: this.labels,
                datasets: this.datasets,
                options: {}
            }
        });
    }
});
