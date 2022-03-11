<template>
<div>
    <button class="btn btn-primary" type="button" @click="showWindow">
        <i class="fa-regular fa-file-circle-plus"></i>
        <span>Tambah Penempatan</span>
    </button>

    <b-modal size='lg' ref="penempatanWindow" hide-header hide-backdrop>

            <b-form-group
             id="input-group-id"
             label="ID Penempatan : "
             label-for="input-id"
             description="Masukan ID baru untuk penempatan yang akan ditambah. ID dapat juga berupa kode panggil penempatan. (max. 5)">
                <b-form-input
                 id="input-id"
                 v-model="form.id"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
             id="input-group-penempatan"
             label="Nama Penempatan : "
             label-for="input-penempatan"
             description="Nama untuk penempatan baru">
                <b-form-input
                 id="input-penempatan"
                 v-model="form.penempatan"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
             id="input-group-kecamatan"
             label="Kecamatan : "
             label-for="input-kecamatan"
             description="(Opsional) Kecamatan dimana penempatan/sektor baru ini ditugaskan">
                <b-form-input
                 id="input-kecamatan"
                 v-model="form.kecamatan"
                 required></b-form-input>
            </b-form-group>

        <template #modal-footer="{}">
            <button type="button" class="btn btn-primary" @click="submitPenempatan()">Tambah Data Penempatan</button>
            <button type="button" class="btn btn-secondary" @click="hideWindow()">Batal</button>
        </template>
    </b-modal>

</div>
</template>
<script>

import BootstrapVue from 'bootstrap-vue';
import axios from 'axios';
import eventbus from '../../eventbus';

Vue.use(BootstrapVue);

export default {
    data(){
        return{
            form:{
                id : '',
                penempatan : '',
                kecamatan :''
            },
        }
    },
    methods:{
        showWindow(){
            this.$refs['penempatanWindow'].show();
        },        
        hideWindow(){
            this.resetForm();
            this.$refs['penempatanWindow'].hide();
        },
        submitPenempatan(){
            axios.post('/admin/action/add-penempatan',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_add_penempatan':    m="Berhasil menambah data penempatan!";
                                                eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                this.hideWindow();
                                                break;
                    case 'fail_add_penempatan_try_caught':m='Gagal menambah data Penempatan! (Error Query/DB)';break;
                    case 'fail_add_penempatan_exist':m='Penempatan dengan data tersebut sudah ada!';break;
                    default:m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(err=>{
                alert('Gagal menambah Penempatan! '+err);
            });
        },

        resetForm(){
                this.id = '',
                this.penempatan = '',
                this.kecamatan =''
        }
    }
}

</script>
