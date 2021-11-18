
window.Vue = require('vue').default;
Vue.component('data-table',require('./components/dataTable.vue').default);
// Vue.component('data-table',require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#dt',
    data(){
        return{
            columns:[
                {data: 'nip'},
                {data: 'nama'},
                {data: 'penempatan'},
                {data: 'jenis_cuti', title:'Jenis Cuti'},
                {data: 'alasan'},
                {data: 'alamat'},
                {data: 'tgl_awal',title:'Tanggal Awal'},
                {data: 'tgl_akhir', title:'Tanggal Akhir'},
                {data: 'total_cuti', title:'Lama Hari'},
                {data: 'tgl_pengajuan', title:'Tanggal Diajukan'},
            ],
            ajax:'/report/table/asn'
        }
      
    },
    // props:{
    //     ajax:'/report/table/asn'
    // }


});

console.log("Test ajax (props) : "+app.$props.ajax+" Test ajax (data) : "+app.ajax+"\nTest Column : "+app.columns);