
window.Vue = require('vue').default;
Vue.component('data-table',require('../components/dataTable.vue').default);

const app = new Vue({
    el: '#dt-self-pjlp',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'jenis_cuti', title:'Jenis Cuti'},
                {data: 'alasan'},
                {data: 'alamat'},
                {data: 'tgl_awal',title:'Tanggal Awal'},
                {data: 'tgl_akhir', title:'Tanggal Akhir'},
                {data: 'total_cuti', title:'Lama Hari'},
                {data: 'tgl_pengajuan', title:'Tanggal Diajukan'},
                {data: 'p_kasie', title:'Persetujuan Kasie'},
                {data: 'k_kasie',title:'Keterangan'},
<<<<<<< Updated upstream
                {data: 'p_tu', title:'Persetujuan TU'},
                {data: 'k_tu',title:'Keterangan'},
                {data: 'p_ppk', title:'Persetujuan PPK'},
                {data: 'k_ppk',title:'Keterangan'},
                {data: 'p_kasudin', title:'Persetujuan Kasudin'},
                {data: 'k_kasudin',title:'Keterangan'},{
=======
                {data: 'p_ppk', title:'Persetujuan PPK'},
                {data: 'k_ppk',title:'Keterangan'},
                {data: 'p_tu', title:'Persetujuan TU'},
                {data: 'k_tu',title:'Keterangan'},
                //{data: 'p_kasudin', title:'Persetujuan Kasudin'},
                //{data: 'k_kasudin',title:'Keterangan'},
                {
>>>>>>> Stashed changes
                    data: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
<<<<<<< Updated upstream
                        let deleteComponent = Vue.extend(require('../components/datatable-buttons/DeleteCuti').default);
                        let updateComponent = Vue.extend(require('../components/datatable-buttons/UpdateCuti').default);
                        let printComponent = Vue.extend(require('../components/datatable-buttons/PrintCuti').default);

                        let deleteButton = new deleteComponent({
                            propsData: rowData,
                        });
=======
                        //let deleteComponent = Vue.extend(require('../components/datatable-buttons/DeleteCuti').default);
                        let updateComponent = Vue.extend(require('../components/datatable-buttons/UpdateCuti').default);
                        let printComponent = Vue.extend(require('../components/datatable-buttons/PrintCuti').default);

                        //let deleteButton = new deleteComponent({
                            //propsData: rowData,
                        //});
>>>>>>> Stashed changes
                        let updateButton = new updateComponent({
                            propsData: rowData
                        });
                        let printButton = new printComponent({
                            propsData:rowData
                        })

<<<<<<< Updated upstream
                        deleteButton.$mount();
=======
                        //deleteButton.$mount();
>>>>>>> Stashed changes
                        updateButton.$mount();
                        printButton.$mount();

                        $(cell).empty()
<<<<<<< Updated upstream
                        .append(deleteButton.$el)
=======
                        //.append(deleteButton.$el)
>>>>>>> Stashed changes
                        .append(updateButton.$el)
                        .append(printButton.$el);
                    }
                }
            ],
            ajax:'/report/table/self'
        }
      
    },


});