/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Laravel and Vue.
 */

require("./bootstrap");

window.Vue = require("vue");
import Vue from "vue";
import ElementUI from "element-ui";
import locale from "element-ui/lib/locale/lang/en";
import LoadScript from "vue-plugin-load-script";

Vue.use(LoadScript);
Vue.use(ElementUI, { locale });

const axios = require('axios');
// if Unauthorized, redirect to login page
axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status === 401) {
        alert("For security reasons, you have been logged out due to inactivity. Please login again to continue your session");
        window.location.href = '/login';
    } 
    return Promise.reject(error);
});

Vue.component("registration", require("./components/Registration.vue").default);
Vue.component("scheduled-events", require("./components/ScheduledEvents.vue").default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
