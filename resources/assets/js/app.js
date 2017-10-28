
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

// Pages
Vue.component('browse-year-view', require('./pages/Browse-year-view.vue'));

Vue.component('example', require('./components/Example.vue'));
Vue.component('flash', require('./components/Flash.vue'));

// Livecounter
Vue.component('category-cards', require('./components/livecounter/CategoryCards.vue'));

// Charts & reports
Vue.component('chart-month', require('./components/charts/chart-month.vue'));
Vue.component('table-report', require('./components/Table-report.vue'));

// Infoboxes
Vue.component('box-sum-sm', require('./components/infoboxes/Box-sum-sm.vue'));
Vue.component('box-avg-sm', require('./components/infoboxes/Box-avg-sm.vue'));

const app = new Vue({
    el: '#app'
});
