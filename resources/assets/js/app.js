
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

import store from './store';

// Vue.component('example', require('./components/Example.vue'));
Vue.component('rhpharma-branches', require('./components/Branches.vue').default);
Vue.component('rhpharma-calendar', require('./components/Calendar.vue').default);
Vue.component('rhpharma-constraints', require('./components/Constraints.vue').default);
Vue.component('rhpharma-constraints-count', require('./components/Constraints-Count.vue').default);
Vue.component('rhpharma-constraints-validator', require('./components/Constraints-Validator.vue').default);
Vue.component('rhpharma-departments', require('./components/Departments.vue').default);
Vue.component('rhpharma-users-departments', require('./components/Departments-Users.vue').default);
Vue.component('rhpharma-holidays', require('./components/Holidays.vue').default);
Vue.component('rhpharma-schedule', require('./components/Schedule.vue').default);
Vue.component('rhpharma-settings-constraint-types', require('./components/Settings-ConstraintTypes.vue').default);
Vue.component('rhpharma-settings-department-user', require('./components/Settings-Department-User.vue').default);
Vue.component('rhpharma-settings-departments', require('./components/Settings-Departments.vue').default);
Vue.component('rhpharma-settings-triplets', require('./components/Settings-Triplets.vue').default);
Vue.component('rhpharma-sortable-table', require('./components/SortableTable.vue').default);
Vue.component('rhpharma-users', require('./components/Users.vue').default);

const app = new Vue({
    el: '#app',
    store
});
