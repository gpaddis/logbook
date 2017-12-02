export default {
    state: {
        rawDatasets: [],
    },

    getters: {
        totalVisits: state => {
            let totalVisits = [];

            for (let key in state.rawDatasets) {
                if (state.rawDatasets.hasOwnProperty(key)) {
                    let dataset = {};
                    dataset['label'] = state.rawDatasets[key].data.label;
                    dataset['data'] = state.rawDatasets[key].data.visits;
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