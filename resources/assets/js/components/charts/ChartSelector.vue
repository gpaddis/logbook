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
    import {mapGetters, mapMutations} from 'vuex';
    import LineChart from './LineChart.vue';

    export default {
        components: { LineChart },

        data() {
            return {
                year: new Date().getFullYear(),
            }
        },

        computed: {
            ...mapGetters(['totalVisits', 'groupedBy'])
        },

        mounted() {
            this.refreshDatasets(this.year);
        },

        methods: {
            ...mapMutations(['clearDatasets', 'pushDataset', 'incrementUpdated']),

            /**
             * Refresh the datasets in the store, loading the year passed
             * and the previous year, then update the properties.
             */
            refreshDatasets(year) {
                this.clearDatasets();

                this.addDataset(year);
                this.addDataset(year - 1);
            },

            /**
             * Fetch a dataset for a given year and commit the mutation.
             * Increment the updated property in the store to trigger
             * re-rendering after the ajax call.
             * 
             * @param {*} year 
             */
            addDataset (year) {
                axios.get('/api/visits/' + year)
                    .then(response => {
                        this.pushDataset(response.data);

                        this.incrementUpdated();
                    });
                }
            }
    }
</script>