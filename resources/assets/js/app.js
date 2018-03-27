
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));
Vue.component('rhpharma-branches', require('./components/Branches.vue'));
Vue.component('rhpharma-constraints', require('./components/Constraints.vue'));
Vue.component('rhpharma-constraints-count', require('./components/Constraints-Count.vue'));
Vue.component('rhpharma-constraints-validator', require('./components/Constraints-Validator.vue'));
Vue.component('rhpharma-departments', require('./components/Departments.vue'));
Vue.component('rhpharma-holidays', require('./components/Holidays.vue'));
Vue.component('rhpharma-schedule', require('./components/Schedule.vue'));
Vue.component('rhpharma-settings-order', require('./components/Settings-Order.vue'));
Vue.component('rhpharma-sortable-table', require('./components/SortableTable.vue'));
Vue.component('rhpharma-users', require('./components/Users.vue'));
Vue.component('rhpharma-users-departments', require('./components/Users-Departments.vue'));
Vue.component('rhpharma-build-buttons', require('./components/Build-Buttons.vue'));

const app = new Vue({
    el: '#app'
});
