<template>
    <table class="table-hover">
        <thead>
        <tr>
            <th v-for="column in parameters.columns" v-html="title(column)" :key="column" ></th>
        </tr>
        </thead>
        <tfoot v-if="footer">
        <tr>
            <th v-for="column in parameters.columns" v-html="column.footer" :key="column" ></th>
        </tr>
        </tfoot>
    </table>
    
</template>


<script>
    window.$ = window.jQuery = require('jquery');
    window.JSZip = require('jszip');
    require('xlsx');

    require('datatables.net');
    require('datatables.net-bs4');
    require('datatables.net-buttons');
    require('datatables.net-buttons-bs4');
    require('datatables.net-buttons/js/buttons.html5.js' );
    require('datatables.net-buttons/js/buttons.print.js' );
 

    import eventbus from '../eventbus';

    export default{
        data() {
            return {
                dataTable: {},
                
            }
        },
        methods: {
            title(column) {
                return column.title || this.titleCase(column.data);
            },
            titleCase(str) {
                return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
            }
        },
        computed: {
            parameters() {
                const vm = this;
                return window.$.extend({
                        serverSide: true,
                        processing: true,
                        dom: '<"mb-1"B><"row align-items-center"<"col text-left"l><"col text-right"f>>r<"table table-responsive"t><"row justify-content-center"p>',
                        buttons:this.buttons,
                        lengthMenu:[[10,25,50,-1],["10","25","50","Semua"]],
                        fixedHeader:true
                   }, {
                   columns:this.columns,
                   ajax:this.ajax,
                   pagingType:'numbers',
                   createdRow(...args) {
                      vm.$emit('created-row', ...args);
                   },
                   drawCallback(...args) {
                      vm.$emit('draw', ...args);
                   },
                   footerCallback(...args) {
                      vm.$emit('footer', ...args);
                   },
                   formatNumber(...args) {
                      vm.$emit('format', ...args);
                   },
                   headerCallback(...args) {
                      vm.$emit('header', ...args);
                   },
                   infoCallback(...args) {
                      vm.$emit('info', ...args);
                   },
                   initComplete(...args) {
                       vm.$emit('init', ...args);
                   },
                   preDrawCallback(...args) {
                      vm.$emit('pre-draw', ...args);
                   },
                   rowCallback(...args) {
                      vm.$emit('draw-row', ...args);
                   },
                   stateLoadCallback(...args) {
                      vm.$emit('state-load', ...args);
                   },
                   stateLoaded(...args) {
                      vm.$emit('state-loaded', ...args);
                   },
                   stateLoadParams(...args) {
                      vm.$emit('state-load-params', ...args);
                   },
                   stateSaveCallback(...args) {
                      vm.$emit('state-save', ...args);
                   },
                   stateSaveParams(...args) {
                      vm.$emit('state-save-params', ...args);
                   },
                }, this.options);
            }
        },
        props: {
            footer: { default: false },
            columns: { type: Array },
            ajax: { default: '' },
            options: { },
            buttons:{ default:[]}
        },
        mounted() {
           this.dataTable = window.$(this.$el).DataTable(this.parameters);

        //    this.dataTable.buttons().container()
        //     .appendTo( $('.dt-buttons', this.dataTable.table().container()))

           eventbus.$on('draw' , (payload)=>{
               console.log("Event Triggered Message : "+payload.message);
               this.dataTable.draw(false);
           });
        },
        destroyed() {
            this.dataTable.destroy();
        }
    }
</script>