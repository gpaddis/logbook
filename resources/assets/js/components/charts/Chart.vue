<template>
    <canvas>
    </canvas>
</template>

<script>
export default {
    props: {
        type: {
            default: 'bar'
        },
        values: {},
    },

    data() {
        return {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        }
    },

    computed: {
        datasets() {
            let datasets = [];

            let entries = new Map(Object.entries(this.values));
            entries.forEach((values, year) => {
                datasets.push({
                    label: year,
                    data: Object.values(values),
                    backgroundColor: 'rgba(255, 12, 2, 0.2)',
                    borderColor: 'rgba(254, 43, 132, 1)',
                    borderWidth: 1
                })
            });

            return datasets;
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
}
</script>
