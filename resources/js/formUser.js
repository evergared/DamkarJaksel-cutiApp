window.Vue = require('vue').default;
Vue.component('tambah-user',require('./components/FormUser.vue').default);

const app = new Vue({
        el:'#form-user',
});