
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import Vue from 'vue';
window.Vue = Vue;

import 'bootstrap-sass'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './store';
import Branches from "./components/Branches.vue";
import ConstraintImporter from "./components/ConstraintImporter.vue";
import Constraints from "./components/Constraints.vue";
import ConstraintsCount from "./components/Constraints-Count.vue";
import ShiftTypes from "./components/ShiftTypes.vue";
import ConstraintsValidator from "./components/Constraints-Validator.vue";
import Departments from "./components/Departments.vue";
import DepartmentsUsers from "./components/Departments-Users.vue";
import Holidays from "./components/Holidays.vue";
import Schedule from "./components/Schedule.vue";
import SettingsConstraintTypes from "./components/Settings-ConstraintTypes.vue";
import SettingsDepartmentUser from "./components/Settings-Department-User.vue";
import SettingsDepartments from "./components/Settings-Departments.vue";
import SettingsTriplets from "./components/Settings-Triplets.vue";
import Shifts from "./components/Shifts.vue";
import SortableTable from "./components/SortableTable.vue";
import Users from "./components/Users.vue";
import Calendar from "./components/Calendar.vue";

// Vue.component('example', require('./components/Example.vue'));
Vue.component('rhpharma-branches', Branches);
Vue.component('rhpharma-calendar', Calendar);
Vue.component('rhpharma-constraint-importer', ConstraintImporter);
Vue.component('rhpharma-constraints', Constraints);
Vue.component('rhpharma-constraints-count', ConstraintsCount);
Vue.component('rhpharma-constraints-validator', ConstraintsValidator);
Vue.component('rhpharma-departments', Departments);
Vue.component('rhpharma-departments-users', DepartmentsUsers);
Vue.component('rhpharma-holidays', Holidays);
Vue.component('rhpharma-schedule', Schedule);
Vue.component('rhpharma-settings-constraint-types', SettingsConstraintTypes);
Vue.component('rhpharma-settings-department-user', SettingsDepartmentUser);
Vue.component('rhpharma-settings-departments', SettingsDepartments);
Vue.component('rhpharma-settings-triplets', SettingsTriplets);
Vue.component('rhpharma-shifts', Shifts);
Vue.component('rhpharma-shift-types', ShiftTypes);
Vue.component('rhpharma-sortable-table', SortableTable);
Vue.component('rhpharma-users', Users);

const app = new Vue({
    el: '#app',
    store
});
