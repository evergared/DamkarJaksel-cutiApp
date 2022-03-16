<template>
<div>
    <button class="btn btn-primary" type="button" @click="showWindow">
        <i class="fa-regular fa-file-circle-plus"></i>
        <span>Tambah jabatan</span>
    </button>

    <b-modal size='lg' ref="jabatanWindow" hide-header hide-backdrop>

            <b-form-group
             id="input-group-id"
             label="ID Jabatan : "
             label-for="input-id"
             description="Masukan ID baru untuk jabatan yang akan ditambah. (max. 4)">
                <b-form-input
                 id="input-id"
                 v-model="form.id"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
             id="input-group-jabatan"
             label="Nama Jabatan : "
             label-for="input-jabatan"
             description="Nama untuk jabatan baru yang akan ditambah.">
                <b-form-input
                 id="input-jabatan"
                 v-model="form.jabatan"
                 required></b-form-input>
            </b-form-group>

        <template #modal-footer="{}">
            <button type="button" class="btn btn-primary" @click="submitjabatan()">Tambah Data Jabatan</button>
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
                jabatan : ''
            },
        }
    },
    methods:{
        showWindow(){
            this.$refs['jabatanWindow'].show();
        },        
        hideWindow(){
            this.resetForm();
            this.$refs['jabatanWindow'].hide();
        },
        submitjabatan(){
            axios.post('/admin/action/add-jabatan',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_add_jabatan':    m="Berhasil menambah data jabatan!";
                                                eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                this.hideWindow();
                                                break;
                    case 'fail_add_jabatan_try_caught':m='Gagal menambah data jabatan! (Error Query/DB)';break;
                    case 'fail_add_jabatan_exist':m='jabatan dengan data tersebut sudah ada!';break;
                    default:m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(err=>{
                alert('Gagal menambah jabatan! '+err);
            });
        },

        resetForm(){
                this.id = '',
                this.jabatan = ''
        }
    }
}

</script>
