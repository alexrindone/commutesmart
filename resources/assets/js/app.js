
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRangedatePicker from 'vue-rangedate-picker';
import DateRangePicker from 'vue2-daterange-picker';
import DatePicker from 'vue2-datepicker'


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('leaderboard', require('./components/Leaderboard.vue'));
Vue.component('profile', require('./components/Profile.vue'));
Vue.component('companies', require('./components/Companies.vue'));
Vue.component('challenges', require('./components/Challenges.vue'));
Vue.component('trips', require('./components/Trips.vue'));
Vue.component('VueRangedatePicker', VueRangedatePicker);
Vue.component('DatePicker', DatePicker);

const app = new Vue({
    el: '#app'
});

