
window.Vue = require('vue').default;
Vue.component('data-table',require('../components/dataTable.vue').default);

const app = new Vue({
    el: '#dt-kasudin-pjlp',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'nip'},
                {data: 'nama'},
                {data: 'jenis_cuti', title:'Jenis Cuti'},
                {data: 'alasan'},
                {data: 'tlpn', title:'Telpon'},
                {data: 'alamat'},
                {data: 'tgl_awal',title:'Tanggal Awal'},
                {data: 'tgl_akhir', title:'Tanggal Akhir'},
                {data: 'total_cuti', title:'Lama Hari'},
                {data: 'tgl_pengajuan', title:'Tanggal Diajukan'},
                {data: 'p_kasie', title:'Persetujuan Kasie'},
                {data: 'k_kasie',title:'Keterangan'},
                {data: 'p_tu', title:'Persetujuan TU'},
                {data: 'k_tu',title:'Keterangan'},
                {data: 'p_ppk', title:'Persetujuan PPK'},
                {data: 'k_ppk',title:'Keterangan'},
                {data: 'p_kasudin', title:'Persetujuan Anda'},
                {data: 'k_kasudin',title:'Keterangan'},
                {
                    data: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let approvalComponent = Vue.extend(require('../components/datatable-buttons/ApprovalCuti').default);

                        let approvalButton = new approvalComponent({
                            propsData: rowData,
                        });

                        approvalButton.$mount();

                        $(cell).empty()
                        .append(approvalButton.$el);
                    }
                }
            ],
        }
      
    },

});