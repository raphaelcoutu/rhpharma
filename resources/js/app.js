/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";

import { createApp } from "vue";

import "bootstrap-sass";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

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
import store from "./store/index.js";

// const app = new Vue({
//     el: '#app'
// });

const app = createApp({})
    .use(store)
    .component("rhpharma-branches", Branches)
    .component("rhpharma-calendar", Calendar)
    .component("rhpharma-constraint-importer", ConstraintImporter)
    .component("rhpharma-constraints", Constraints)
    .component("rhpharma-constraints-count", ConstraintsCount)
    .component("rhpharma-constraints-validator", ConstraintsValidator)
    .component("rhpharma-departments", Departments)
    .component("rhpharma-departments-users", DepartmentsUsers)
    .component("rhpharma-holidays", Holidays)
    .component("rhpharma-schedule", Schedule)
    .component("rhpharma-settings-constraint-types", SettingsConstraintTypes)
    .component("rhpharma-settings-department-user", SettingsDepartmentUser)
    .component("rhpharma-settings-departments", SettingsDepartments)
    .component("rhpharma-settings-triplets", SettingsTriplets)
    .component("rhpharma-shifts", Shifts)
    .component("rhpharma-shift-types", ShiftTypes)
    .component("rhpharma-sortable-table", SortableTable)
    .component("rhpharma-users", Users)
    .mount("#app");
