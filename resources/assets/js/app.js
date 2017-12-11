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

// Global components
Vue.component('flash', require('./components/flash.vue'));
Vue.component('example', require('./components/Example.vue'));
Vue.component('category-cards', require('./components/livecounter/category-cards.vue'));

// Logbook browse views
Vue.component('year-view', require('./pages/Year.vue'));
Vue.component('overview-view', require('./pages/Overview.vue'));

// Charts
Vue.component('chart-selector', require('./components/charts/ChartSelector.vue'));
Vue.component('bar-chart', require('./components/charts/BarChart.vue'));


// Import the global store (state management) object.
import Vuex from 'vuex';
Vue.use(Vuex);

import store from './store';

// The Vue instance.
const app = new Vue({
    el: '#app',

    store: new Vuex.Store(store)
});
