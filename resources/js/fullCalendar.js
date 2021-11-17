window.Vue = require('vue').default;
Vue.component('admin-calendar', require('./components/AdminCalendar.vue').default);

const app = new Vue({
    el:"#app",
});