import Vue from 'vue';
import Chart from 'chart.js';

/**
 * This is the master level chart to be subclassed by all specific charts.
 */
 export default Vue.extend({
    template: `<canvas></canvas>`,

    data() {
        return {
            backgroundColors: ['#ff6384', '#36a2eb', '#cc65fe'],
            borderColors: ['#ff6384', '#36a2eb', '#cc65fe']
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
