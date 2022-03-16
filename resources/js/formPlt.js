window.Vue = require('vue').default;
Vue.component('form-plt',require('./components/FormPLT.vue').default);

const app = new Vue({
    el:"#plt",
});