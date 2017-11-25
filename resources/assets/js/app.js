
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Then we create our central store, the state container that will share
 * what is needed when the components need to access it.
 */
var store = {
    debug: true,
    state: {
        datasets: []
    },
    addDataset(dataset) {
        if (this.debug) console.log('dataset added:', dataset);
        this.state.datasets.push(dataset);
    },
    clearDatasets() {
        if (this.debug) console.log('clearDatasets action triggered');
        this.state.datasets = [];
    }
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('flash', require('./components/flash.vue'));

// Livecounter
Vue.component('category-cards', require('./components/livecounter/category-cards.vue'));

// Charts & reports
Vue.component('selector-years', require('./components/charts/SelectorYears.vue'));
Vue.component('chart-month', require('./components/charts/chart-month.vue'));
Vue.component('chart-categories', require('./components/charts/chart-categories.vue'));
Vue.component('table-report', require('./components/table-report.vue'));

// Infoboxes
Vue.component('box-sum-sm', require('./components/infoboxes/box-sum-sm.vue'));
Vue.component('box-avg-sm', require('./components/infoboxes/box-avg-sm.vue'));
Vue.component('box-generic-lg', require('./components/infoboxes/box-generic-lg.vue'));

const app = new Vue({
    el: '#app'
});
