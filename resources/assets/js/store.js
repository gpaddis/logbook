export default {
    state: {
        datasets: [],
        labels: []
    },

    getters: {
        datasets: {
            /**
             * Foreach dataset in the state property "dataset"
             * return the datasets in the format expected
             * by chart.js.
             * Change the name of state.datasets and relative calls.
             */
        }
    },

    mutations: {
        /**
         * Add a dataset to the state once it has been retrieved.
         * 
         * @param {*} state 
         * @param {*} dataset 
         */
        addDataset (state, dataset) {
            state.datasets.push(dataset);
        },

        /**
         * Clear the datasets.
         * 
         * @param {*} state 
         */
        clearDatasets (state) {
            state.datasets = [];
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
                .then(response => context.commit('addDataset', response.data));
        }
    }
};