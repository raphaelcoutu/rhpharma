
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

import VueFlatpickr from 'vue-flatpickr';
import 'vue-flatpickr/theme/airbnb.css'

// Vue.component('example', require('./components/Example.vue'));
Vue.component('rhpharma-branches', require('./components/Branches.vue'));
Vue.component('rhpharma-users', require('./components/Users.vue'));
Vue.component('rhpharma-departments', require('./components/Departments.vue'));
Vue.component('rhpharma-schedule', require('./components/Schedule.vue'));
Vue.component('rhpharma-sortable-table', require('./components/SortableTable.vue'));
Vue.use(VueFlatpickr);

const app = new Vue({
    el: '#app'
});
