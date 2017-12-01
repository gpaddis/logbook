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
Vue.component('category-cards', require('./components/livecounter/category-cards.vue'));

// Logbook browse views
Vue.component('year-view', require('./pages/Year.vue'));
Vue.component('overview-view', require('./pages/Overview.vue'));

// Import the global store (state management) object.
import store from './store';
import Vuex from 'vuex';

// The Vue instance.
const app = new Vue({
    el: '#app',

    data: {
        sharedState: store.state
    }
});
