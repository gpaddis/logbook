export default {
    debug: true,
    state: {
        datasets: []
    },
    addDatasetAction(newDataset) {
        if (this.debug) console.log('addDatasetAction triggered with', newDataset);
        this.state.datasets.push(newDataset);
    },
    clearDatasetsAction() {
        if (this.debug) console.log('clearDatasetsAction triggered');
        this.state.datasets = ['']
    }
};