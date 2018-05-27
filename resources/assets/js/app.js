
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

Vue.component('employees', require('./components/Employees.vue'));
Vue.component('employees-tree', require('./components/EmployeesTree.vue'));
import VModal from 'vue-js-modal';
import VeeValidate from 'vee-validate';

require('select2');

Vue.use(VModal, { dynamic: true });
Vue.use(VeeValidate, {
    events: 'blur|change|custom'
});


const app = new Vue({
    el: '#app'
});
