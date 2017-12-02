export default {
    state: {
        rawDatasets: []
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

                    // Let's define the labels first.
                    dataset['label'] = state.rawDatasets[key].data.label;
                    
                    // Sum the visits for all categories and store them in the 'data' index.
                    let visits = [];
                    for (let month in state.rawDatasets[key].data.visits) {
                        if (state.rawDatasets[key].data.visits.hasOwnProperty(month)) {
                            visits[month] = Object.values(state.rawDatasets[key].data.visits[month]).reduce((a, b) => a + b);
                        }
                    }
                    dataset['data'] = visits;

                    totalVisits.push(dataset);
                }
            }
            
            return totalVisits;  
        },

        /**
         * Get the time unit which the datasets are grouped by.
         */
        groupedBy: state => {
            if (state.rawDatasets.hasOwnProperty(0)) {
                return state.rawDatasets[0].data.groupedBy;
            }
        },

        /**
         * Check if the data was loaded.
         */
        isLoaded: state => {
            if (state.rawDatasets.hasOwnProperty(0)) {
                return true;
            }

            return false;
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