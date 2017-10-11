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
        label: {},
        labels: {},
        values: {},
        backgroundColor: {},
        borderColor: {}
    },

    mounted() {
        var myChart = new Chart(this.$el, {
            type: this.type,
            data: {
                labels: this.labels,
                datasets: [{
                    label: this.label,
                    data: this.values,
                    backgroundColor: this.backgroundColor,
                    borderColor: this.borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    }
}
</script>
