
window.Vue = require('vue').default;
Vue.component('data-table',require('./components/dataTable.vue').default);

const app = new Vue({
    el:"#dt",
    data() {
        return {
            columns:[
                {data: 'nip'},
                {data: 'nama'},
                {data: 'penempatan'},
                {data: 'jenis_cuti'},
                {data: 'alasan'},
                {data: 'alamat'},
                {data: 'tgl_awal'},
                {data: 'tgl_akhir'},
                {data: 'total_cuti'},
                {data: 'tgl_pengajuan'},
            ],
            ajax:'/report/table/asn'
        }
    }

    // columns:[
    //     {data: 'nip'},
    //     {data: 'nama'},
    //     {data: 'penempatan'},
    //     {data: 'jenis_cuti'},
    //     {data: 'alasan'},
    //     {data: 'alamat'},
    //     {data: 'tgl_awal'},
    //     {data: 'tgl_akhir'},
    //     {data: 'total_cuti'},
    //     {data: 'tgl_pengajuan'},
    // ],
    // ajax:'/report/table/asn'
});