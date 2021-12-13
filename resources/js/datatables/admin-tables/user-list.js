window.Vue = require('vue').default;
Vue.component('data-table',require('../../components/dataTable.vue').default);

const app = new Vue({
    el:'#dt-user-list',
    data(){
        return{
            columns:[
                {data: 'DT_RowIndex', title:'no'},
                {data: 'nip'},
                {data: 'roles', title:'level'},
                {data: 'email'},
                {data: 'email',
                    title: 'tindakan',
                    orderable: false,
                    searchable: false,
                    createdCell(cell,cellData,rowData){
                        let updateComponent = Vue.extend(require('../../components/datatable-buttons/UserChangePassword').default);

                        
                        let updateButton = new updateComponent({
                            propsData: rowData
                        });
                       

                        updateButton.$mount();

                        $(cell).empty().append(updateButton.$el);
                    }
                }
            ],
            ajax: '/user/list'
        }
    }
})