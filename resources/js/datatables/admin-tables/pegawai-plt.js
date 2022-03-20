window.Vue = require('vue').default;
Vue.component('data-table',require('../../components/dataTable.vue').default);

const app = new Vue({
    el:'#dt-pegawai-plt',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'nip', title:'nip'},
                {data: 'nama', title:'nama'},
                {data: 'penempatan', title:'asal penempatan'},
                {data: 'jabatan', title:'Jabatan yang Dilaksanakan'},
                {data: 'tindakan',
                    title: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let deleteComponent = Vue.extend(require('../../components/datatable-buttons/PLTDelete').default);

                        let deleteButton = new deleteComponent({
                            propsData: rowData
                        })

                        deleteButton.$mount();

                        $(cell).empty()
                        .append(deleteButton.$el);
                    }
                }
            ],
            buttons:[]
        }
    },
    mounted(){

        let test = Vue.extend(require('../../components/datatable-toolbar/PltAdd.vue').default);
        let t1 = new test();

        let components = [t1];
        let tBawah = $("#dt-pegawai-plt div.tBawah");
        
        tBawah.empty();
        components.forEach(n => {
            n.$mount();
            tBawah.append(n.$el);
        });

    }
})