<template>
  <div class="card-body">
    <h4 class="card-title">Load the statistics</h4>
    <p class="card-text">Select a year from the dropdown menu to update the graph.
      The data will be compared with the same period of the previous year (if available).</p>

    <label class="mr-sm-2" for="dateSelector">Year</label>
    <select v-model="year" class="custom-select mb-2 mr-sm-2 mb-sm-0" id="dateSelector">
      <option v-for="year in sortedYears" :value="year" :key="year">{{ year }}</option>
    </select>

    <button class="btn btn-primary" @click="refreshDatasets(year)">
    Reload <i class="fa fa-refresh fa-fw" :class="{ 'fa-spin': loading }"></i>
    </button>
  </div>
</template>

<script>
    import {mapState, mapGetters, mapMutations, mapActions} from 'vuex';

    export default {
        props: ['yearsAvailable'],

        data() {
            return {
                year: new Date().getFullYear(),
                options: {
                    groupBy: null
                }
            }
        },

        computed: {
            ...mapState(['updated']),
            ...mapGetters(['totalVisits', 'groupedBy']),

            /**
             * "Loading" is true until both datasets have been loaded.
             */
            loading() {
                return this.updated % 2 !== 0;
            },

            /**
             * Return a sorted array of the years available in the database.
             */
            sortedYears() {
                return Object.values(this.yearsAvailable).sort();
            }
        },

        mounted() {
            this.refreshDatasets(this.year);
        },

        methods: {
            ...mapMutations(['clearDatasets', 'pushDataset', 'incrementUpdated']),
            ...mapActions(['addDataset']),

            /**
             * Generate the URL for the AJAX call based on the parameters passed and append
             * the options set in this.options (if any).
             */
            generateUrl(year) {
                let url = '/api/visits/' + year;

                if (this.options.groupBy !== null) {
                    url += '?groupBy=' + this.options.groupBy;
                }

                return url;
            },

            /**
             * Refresh the datasets in the store, loading the year passed and the previous year,
             * then update the datasets in the state at the given index.
             */
            refreshDatasets(year) {
                this.clearDatasets();

                this.addDataset({
                    url: this.generateUrl(year - 1),
                    index: 0
                    });

                this.addDataset({
                    url: this.generateUrl(year),
                    index: 1
                    });
            },
        }
    }
</script>