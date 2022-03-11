window.Vue = require('vue').default;
Vue.component('form-pegawai',require('./components/FormPegawai.vue').default);

const app = new Vue({
    el:"#pegawai",
});