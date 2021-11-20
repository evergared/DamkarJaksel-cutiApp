window.Vue = require('vue').default;
Vue.component('test',require('./components/datatable-buttons/TestButton.vue').default);

const app = new Vue({
    el:"#test",
});