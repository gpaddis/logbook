export default {
    state: {
        rawDatasets: null
    },

    getters: {
        /**
         * Get an array of datasets containing the total number of visits for the period
         * selected. The datasets are formatted to be used with chart.js.
         */
        totalVisits: state => {
            let datasets = [];

            for (let key in state.rawDatasets) {
                if (state.rawDatasets.hasOwnProperty(key)) {
                    // Sum the visits
                    let totals = [];

                    for (let unit in state.rawDatasets[key].data.visits) {
                        if (state.rawDatasets[key].data.visits.hasOwnProperty(unit)) {
                            totals[unit] = Object.values(state.rawDatasets[key].data.visits[unit])
                                .reduce((a, b) => a + b);
                        }
                    }

                    datasets.push({
                        data: totals,
                        label: state.rawDatasets[key].data.label
                    });

                }
            }
                
            return datasets;  
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
            if (state.rawDatasets !== null) {
                return true;
            }
            
            console.log('not yet');
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
    }
};