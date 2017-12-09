<template>
    <div class="col">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h4 class="card-title">Load the statistics</h4>
                    <p class="card-text">Select a year from the dropdown menu to update the graph. 
                        The data will be compared with the same period of the previous year (if available).</p>
                </div>

                <div class="col">
                    <label class="mr-sm-2" for="dateSelector">Year</label>
                    <select v-model="year" class="custom-select mb-2 mr-sm-2 mb-sm-0" id="dateSelector">
                        <option v-for="(year, index) in yearsAvailable" :value="year" :key="index">{{ year }}</option>
                    </select>

                   <button class="btn btn-primary" @click="refreshDatasets(year)">Load</button>
                </div>
            </div>
            

            
        </div>

        <bar-chart :labels="labels"></bar-chart>
    </div>
</template>

<script>
    import {mapGetters, mapMutations, mapActions} from 'vuex';
    import BarChart from './BarChart.vue';

    export default {
        components: { BarChart },

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
            ...mapGetters(['totalVisits', 'groupedBy']),

            /** 
             * Return the labels according to the groupedBy property returned with the ajax call.
             */
            labels() {
                switch (this.groupedBy) {
                    case 'month':
                        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        break;

                    case 'day':
                        return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
                        break;
                    
                    case 'hour':
                        return [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
                        break;
                
                    default:
                        return null;
                        break;
                }
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
                    url += '&groupBy=' + this.options.groupBy;
                }

                return url;
            },

            /**
             * Refresh the datasets in the store, loading the year passed
             * and the previous year, then update the properties.
             */
            refreshDatasets(year) {
                this.clearDatasets();

                this.addDataset(this.generateUrl(year));
                this.addDataset(this.generateUrl(year - 1));
            },
        }
    }
</script>