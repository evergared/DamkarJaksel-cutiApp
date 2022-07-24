window.Vue = require('vue').default;
Vue.component('pengumuman-creator',require('./components/PengumumanCreator.vue').default);
Vue.component('pengumuman-preview',require('./components/PengumumanPreview.vue').default);

const app = new Vue({
    el:'#pengumuman-creator-page'
})