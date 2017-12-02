export default {
    state: {
        rawDatasets: [],
    },

    getters: {
        /**
         * Get an array of datasets containing the total number of visits for the period
         * selected. The datasets are formatted to be used with chart.js.
         */
        totalVisits: state => {
            let totalVisits = [];
            for (let key in state.rawDatasets) {
                if (state.rawDatasets.hasOwnProperty(key)) {
                    let dataset = {};

                    // Let's define the labels first
                    dataset['label'] = state.rawDatasets[key].data.label;
                    
                    let visits = [];
                    for (const month in state.rawDatasets[key].data.visits) {
                        if (state.rawDatasets[key].data.visits.hasOwnProperty(month)) {
                            visits[month] = Object.values(state.rawDatasets[key].data.visits[month]).reduce((a, b) => a + b);
                        }
                    }
                    dataset['data'] = visits;

                    // state.rawDatasets[key].data.visits;
                    totalVisits.push(dataset);
                }
            }
            
            return totalVisits;  
        } 
    },

    mutations: {
        /**
         * Push a dataset to the current state once it has been retrieved.
         * 
         * @param {*} state 
         * @param {*} dataset 
         */
        pushDataset (state, dataset) {
            state.rawDatasets.push(dataset);
        },

        /**
         * Empty the raw datasets.
         * 
         * @param {*} state 
         */
        clearDatasets (state) {
            state.rawDatasets = [];
        }
    },

    actions: {
        /**
         * Fetch a dataset for a given year and commit the mutation.
         * 
         * @param {*} context 
         * @param {*} year 
         */
        addDataset (context, year) {
            axios.get('/api/visits/' + year)
                .then(response => context.commit('pushDataset', response.data));
        }
    }
};