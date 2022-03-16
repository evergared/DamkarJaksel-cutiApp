window.Vue = require('vue').default;
Vue.component('data-table',require('../../components/dataTable.vue').default);

const app = new Vue({
    el:'#dt-pegawai-master',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'nip', title:'nip'},
                {data: 'nrk', title:'nrk'},
                {data: 'nama', title: 'nama'},
                {data: 'golongan', title:'golongan'},
                {data: 'pendidikan',title: 'pendidikan'},
                {data: 'jabatan', title:'jabatan'},
                {data: 'penempatan', title:'penempatan'},
                {data: 'grup', title:'Group Piket'},
                {data: 'tindakan',
                    title: 'tindakan',
                    orderable: false,
                    searchable: false,
                    fixed:true,
                    createdCell(cell,cellData,rowData){
                        let updateComponent = Vue.extend(require('../../components/datatable-buttons/PegawaiUpdate').default);
                        let deleteComponent = Vue.extend(require('../../components/datatable-buttons/PegawaiDelete').default);
                        
                        let updateButton = new updateComponent({
                            propsData: rowData
                        });

                        let deleteButton = new deleteComponent({
                            propsData: rowData
                        })
                       

                        updateButton.$mount();
                        deleteButton.$mount();

                        $(cell).empty()
                        .append(updateButton.$el)
                        .append(deleteButton.$el);
                    }
                }
            ],
        }
    },

    mounted(){

        let test = Vue.extend(require('../../components/datatable-toolbar/PegawaiAdd.vue').default);
        let t1 = new test();

        let components = [t1];
        let tBawah = $("#dt-pegawai-master div.tBawah");
        
        tBawah.empty();
        components.forEach(n => {
            n.$mount();
            tBawah.append(n.$el);
        });

    }
})