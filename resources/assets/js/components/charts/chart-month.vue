<script>
import Graph from './Graph';

export default Graph.extend({
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
            let index = 0;

            entries.forEach((values, year) => {
                datasets.push({
                    label: year,
                    data: Object.values(values),
                    backgroundColor: this.backgroundColors[index],
                    borderColor: this.borderColors[index++],
                    borderWidth: 1
                })
            });

            return datasets;
        }
    }
});
</script>
