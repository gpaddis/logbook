<script>
import Graph from './Graph';

export default Graph.extend({
    props: {
        type: {
            default: 'doughnut'
        },
        keys: {
            required: true
        },
        values: {
            required: true
        }
    },

    data() {
        return {
            datasets: [{
                data: this.values,
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#f9b248', '#fc3a52', '#ff6384', '#36a2eb', '#cc65fe']
            }],

            labels: this.keys,
        }
    }
});
</script>
