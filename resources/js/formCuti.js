window.Vue = require('vue').default;
Vue.component('form-cuti',require('./components/FormCuti.vue').default);

const app = new Vue({
    el:"#cuti",
});