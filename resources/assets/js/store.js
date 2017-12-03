export default {
    state: {
        /**
         * The raw data coming from the API.
         */
        rawDatasets: [],

        /**
         * Keep a count of the dataset updates to allow reactivity even with nested arrays.
         */
        updated: 0,

        backgroundColors: ['rgba(249, 178, 72, 0.5)', 'rgba(252, 58, 82, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(204, 101, 254, 0.5)'],
        borderColors: ['rgba(249, 178, 72, 0.8)', 'rgba(252, 58, 82, 0.8)', 'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(204, 101, 254, 0.8)']
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
                            let newIndex = unit - 1; // Shift all indexes to match the labels in the graph.
                            totals[newIndex] = Object.values(state.rawDatasets[key].data.visits[unit])
                                .reduce((a, b) => a + b);
                        }
                    }

                    // Push the dataset into an array in a format readable by chart.js.
                    datasets.push({
                        data: totals,
                        label: state.rawDatasets[key].data.label,
                        backgroundColor: state.backgroundColors[key],
                        borderColor: state.borderColors[key],
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