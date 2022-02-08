
window.Vue = require('vue').default;
Vue.component('data-table',require('../components/dataTable.vue').default);

const app = new Vue({
    el: '#dt-kasieppk-pjlp',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'nip'},
                {data: 'nama'},
                {data: 'penempatan'},
                {data: 'jenis_cuti', title:'Jenis Cuti'},
                {data: 'alasan'},
                {data: 'tlpn', title:'Telpon'},
                {data: 'alamat'},
                {data: 'tgl_awal',title:'Tanggal Awal'},
                {data: 'tgl_akhir', title:'Tanggal Akhir'},
                {data: 'total_cuti', title:'Lama Hari'},
                {data: 'tgl_pengajuan', title:'Tanggal Diajukan'},
                {data: 'p', title:'Persetujuan Anda'},
                {data: 'k',title:'Keterangan'},
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