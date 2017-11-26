
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

// Logbook browse views
Vue.component('year-view', require('./pages/Year.vue'));

// Infoboxes
Vue.component('box-sum-sm', require('./components/infoboxes/box-sum-sm.vue'));
Vue.component('box-avg-sm', require('./components/infoboxes/box-avg-sm.vue'));
Vue.component('box-generic-lg', require('./components/infoboxes/box-generic-lg.vue'));

import store from './store';

const app = new Vue({
    el: '#app',

    data: {
        sharedState: store.state
    }
});
