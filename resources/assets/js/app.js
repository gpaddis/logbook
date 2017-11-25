
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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
Vue.component('selector-years', require('./components/charts/SelectorYear.vue'));
Vue.component('chart-month', require('./components/charts/chart-month.vue'));
Vue.component('chart-categories', require('./components/charts/chart-categories.vue'));
Vue.component('table-report', require('./components/table-report.vue'));

// Infoboxes
Vue.component('box-sum-sm', require('./components/infoboxes/box-sum-sm.vue'));
Vue.component('box-avg-sm', require('./components/infoboxes/box-avg-sm.vue'));
Vue.component('box-generic-lg', require('./components/infoboxes/box-generic-lg.vue'));

/**
 * The app is our central store, it contains the datasets to share with
 * the other components, when they need to access them.
 */
const app = new Vue({
    el: '#app',

    data: {
        datasets: [],
        debug: true
    },

    methods: {
        addDataset(dataset) {
            if (this.debug) console.log('dataset added:', dataset);
            this.datasets.push(dataset);
        },
        clearDatasets() {
            if (this.debug) console.log('clearDatasets action triggered');
            this.datasets = [];
        }
    }
});
