import { Line } from 'vue-chartjs'

export default {
    extends: Line,
    
    data() {
        return {
            data: {
                labels: ['January', 'February'],
                datasets: [
                    {
                        label: 'GitHub Commits',
                        backgroundColor: '#f87979',
                        data: [40, 20]
                    }
                ]
            }
        }
    },

    mounted() {
        // Overwriting base render method with actual data.
        this.renderChart(this.data, {})
    }
}