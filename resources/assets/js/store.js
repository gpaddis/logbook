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
        },

        /** 
         * Return the labels according to the groupedBy property returned with the ajax call.
         */
        labels: state => {
            let grouping = null;

            if (state.rawDatasets.hasOwnProperty(0)) {
                grouping = state.rawDatasets[0].data.groupedBy;
            }

            switch (grouping) {
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

    mutations: {
        /**
         * Push a dataset to the current state at a given index once it has been retrieved.
         * The payload must contain a dataset and an index.
         * 
         * @param {*} state 
         * @param {*} payload 
         */
        pushDataset (state, payload) {
            state.rawDatasets.splice(payload.index, 0, payload.dataset);
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
    },

    actions: {
        /**
         * Fetch a dataset from the given url and commit the mutation.
         * Increment the updated property in the store to trigger
         * re-rendering after the ajax call.
         * 
         * @param {*} context
         * @param {*} payload
         */
        addDataset({ commit }, payload) {
            axios.get(payload.url)
            .then(response => {
                commit('pushDataset', { 
                    dataset: response.data,
                    index: payload.index
                });

                commit('incrementUpdated');
            })
        }
    }
};