window.Vue = require('vue').default;
Vue.component('data-table',require('../../components/dataTable.vue').default);

const app = new Vue({
    el:'#dt-pegawai-penempatan',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'kode_panggil', title:'id'},
                {data: 'penempatan', title:'nama'},
                {data: 'kecamatan', title:'kecamatan'},
                {data: 'tindakan',
                    title: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let deleteComponent = Vue.extend(require('../../components/datatable-buttons/PenempatanDelete').default);
                        

                        let deleteButton = new deleteComponent({
                            propsData: rowData
                        })
                       

                        deleteButton.$mount();

                        $(cell).empty()
                        .append(deleteButton.$el);
                    }
                }
            ],
        }
    },

    mounted(){

        let test = Vue.extend(require('../../components/datatable-toolbar/PenempatanAdd.vue').default);
        let t1 = new test();

        let components = [t1];
        let tBawah = $("#dt-pegawai-penempatan div.tBawah");
        
        tBawah.empty();
        components.forEach(n => {
            n.$mount();
            tBawah.append(n.$el);
        });

    }
})