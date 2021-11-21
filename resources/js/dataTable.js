
window.Vue = require('vue').default;
Vue.component('data-table',require('./components/dataTable.vue').default);
// Vue.component('data-table',require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#dt',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
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
                {
                    data: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let deleteComponent = Vue.extend(require('./components/datatable-buttons/DeleteCuti').default);
                        let updateComponent = Vue.extend(require('./components/datatable-buttons/UpdateCuti').default);

                        let deleteButton = new deleteComponent({
                            propsData: rowData,
                        });
                        let updateButton = new updateComponent({
                            propsData: rowData
                        });

                        deleteButton.$mount();
                        updateButton.$mount();

                        $(cell).empty()
                        .append(deleteButton.$el)
                        .append(updateButton.$el);
                    }
                }
            ],
            ajax:'/report/table/asn'
        }
      
    },
    // props:{
    //     ajax:'/report/table/asn'
    // }


});