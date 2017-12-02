<template>
    <div class="col">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            <h4 class="card-title">Load new datasets</h4>
            <p class="card-text">Click on the button to load new datasets.</p>

            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" @click="refreshDatasets(year)">Load</button>
                </span>
                <input type="text" class="form-control" placeholder="Type in a year" aria-label="Search for..." v-model="year">
            </div>
        </div>

        <line-chart></line-chart>
    </div>
</template>

<script>
    import {mapGetters, mapActions, mapMutations} from 'vuex';
    import LineChart from './LineChart';

    export default {
        components: { LineChart },

        data() {
            return {
                year: new Date().getFullYear(),

                datacollection: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: this.totalVisits
                }
            }
        },

        computed: {
            ...mapGetters(['totalVisits', 'groupedBy', 'isLoaded'])
        },

        mounted() {
            this.refreshDatasets(this.year);
        },

        methods: {
            ...mapActions(['addDataset']),
            ...mapMutations(['clearDatasets']),

            /**
             * Load the datasets in the data collection.
             */
            loadDatasets () {
                this.datacollection.datasets = this.totalVisits;
            },

            /**
             * Refresh the datasets in the store, loading the year passed
             * and the previous year, then update the properties.
             */
            refreshDatasets(year) {
                this.clearDatasets();

                this.addDataset(year);
                this.addDataset(year - 1);

                this.loadDatasets();
            }
        },
    }
</script>