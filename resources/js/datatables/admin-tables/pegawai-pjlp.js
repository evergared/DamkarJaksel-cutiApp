window.Vue = require('vue').default;
Vue.component('data-table',require('../../components/dataTable.vue').default);

const app = new Vue({
    el:'#dt-pegawai-pjlp',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'nip', title:'nip'},
                {data: 'nama', title: 'nama'},
                {data: 'pendidikan',title: 'pendidikan'},
                {data: 'jabatan', title:'jabatan'},
                {data: 'penempatan', title:'penempatan'},
                {data: 'grup', title:'Group Piket'},
                {data: 'tindakan',
                    title: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let updateComponent = Vue.extend(require('../../components/datatable-buttons/UserChangePassword').default);
                        let levelComponent = Vue.extend(require('../../components/datatable-buttons/UserChangeLevel').default);
                        let deleteComponent = Vue.extend(require('../../components/datatable-buttons/UserDelete').default);
                        
                        let updateButton = new updateComponent({
                            propsData: rowData
                        });
                        let levelButton = new levelComponent({
                            propsData: rowData
                        });
                        let deleteButton = new deleteComponent({
                            propsData: rowData
                        })
                       

                        updateButton.$mount();
                        levelButton.$mount();
                        deleteButton.$mount();

                        $(cell).empty()
                        // .append(updateButton.$el)
                        // .append(levelButton.$el)
                        // .append(deleteButton.$el);
                    }
                }
            ],
        }
    }
})