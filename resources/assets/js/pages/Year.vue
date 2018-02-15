<script>
    import SelectorYear from '../components/charts/SelectorYear.vue';
    import ChartMonth from '../components/charts/ChartMonth.vue';
    import ChartCategories from '../components/charts/ChartCategories.vue';
    import TableReport from '../components/TableReport.vue';
    import BoxSumSm from '../components/infoboxes/BoxSumSm.vue';
    import BoxAvgSm from '../components/infoboxes/BoxAvgSm.vue';

    export default {
        components: {
            SelectorYear, 
            ChartMonth, 
            ChartCategories, 
            TableReport,
            BoxSumSm,
            BoxAvgSm
        },

        data() {
            return {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: []
            }
        },

        methods: {
            refreshDatasets(year) {
                this.resetDatasets();

                this.fetch(year);
                this.fetch(year - 1);
            },

            resetDatasets() {
                this.datasets = [];
            },

            fetch(year) {
                axios.get('/api/visits/' + year)
                .then(response => this.addDataset(response.data));
            },

            addDataset(data) {
                let dataset = this.prepareDataset(data);
                this.datasets.push(dataset);
            },

            prepareDataset(response) {
                return {
                    label: response.data.label,
                    data: response.data.visits
                };
            }
        }
    }
</script>