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
                        let deleteComponent = Vue.extend(require('../../components/datatable-buttons/UserDelete').default);
                        
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
            ajax: '/user/list'
        }
    }
})