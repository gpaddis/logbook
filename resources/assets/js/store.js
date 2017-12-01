export default {
    state: {
        datasets: []
    },

    mutations: {
        addDataset(state) {
            state.datasets.push('new dataset.');
        }
    }
};