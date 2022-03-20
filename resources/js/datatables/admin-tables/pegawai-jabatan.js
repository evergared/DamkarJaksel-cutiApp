window.Vue = require('vue').default;
Vue.component('data-table',require('../../components/dataTable.vue').default);

const app = new Vue({
    el:'#dt-pegawai-jabatan',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'no', title:'id jabatan'},
                {data: 'jabatan', title:'nama jabatan'},
                {data: 'tindakan',
                    title: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let deleteComponent = Vue.extend(require('../../components/datatable-buttons/JabatanDelete').default);
                        

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

        let test = Vue.extend(require('../../components/datatable-toolbar/JabatanAdd.vue').default);
        let ppkComponent = Vue.extend(require('../../components/datatable-toolbar/JabatanPpk.vue').default);
        let t1 = new test();
        let ppk = new ppkComponent();

        let components = [t1,ppk];
        let tBawah = $("#dt-pegawai-jabatan div.tBawah");
        
        tBawah.empty();
        components.forEach(n => {
            n.$mount();
            tBawah.append(n.$el);
        });

    }
})