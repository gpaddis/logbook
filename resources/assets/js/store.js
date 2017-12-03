export default {
    state: {
        /**
         * The raw data coming from the API.
         */
        rawDatasets: null,

        /**
         * Keep a count of the dataset updates to allow reactivity even with nested arrays.
         */
        updated: 0
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
                    // Get rid of the patron categories, sum all the visits and store them in an array.
                    let totals = [];

                    for (let unit in state.rawDatasets[key].data.visits) {
                        if (state.rawDatasets[key].data.visits.hasOwnProperty(unit)) {
                            totals[unit] = Object.values(state.rawDatasets[key].data.visits[unit])
                                .reduce((a, b) => a + b);
                        }
                    }

                    // Push the dataset into an array in a format readable by chart.js.
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
        },

        /**
         * Increment the updated property.
         * 
         * @param {*} state 
         */
        incrementUpdated(state) {
            state.updated++;
        }
    }
};