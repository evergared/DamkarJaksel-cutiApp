<template>
<div>
    <button class="btn btn-primary" type="button" @click="showWindow">
        <i class="fa-regular fa-file-circle-plus"></i>
        <span>Tambah Pelaksana Tugas</span>
    </button>

    <b-modal size='lg' ref="pltWindow" hide-header hide-backdrop>

            <b-form-group
             id="input-group-nip"
             label="NIP Pegawai : "
             label-for="input-nip"
             description="NIP pegawai yang akan menjadi Pelaksana Tugas">
                <b-form-input
                 id="input-nip"
                 v-model="form.nip"
                 required></b-form-input>
            </b-form-group>

            <b-form-group 
            id="input-group-jabatan"
            label="Jabatan PLT : "
            label-for="input-jabatan"
            description="Pilih jabatan yang akan dilaksanakan oleh Pelaksana Tugas">
                <b-form-select
                v-model="form.kode_jabatan"
                :options="opsiJabatan"
                ></b-form-select>
            </b-form-group>

        <template #modal-footer="{}">
            <button type="button" class="btn btn-primary" @click="submitPlt()">Tambah PLT</button>
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
    mounted(){
        axios.get('/admin/list-jabatan')
        .then(resp => {
            let d = resp.data;
            d.push({value:null,text:'Pilih Jabatan'});
            this.opsiJabatan = d;
        })
        .catch(err => {
            console.log('error fetch list jabatan on PLT Add : '+err);
        });
    },

    data(){
        return{
            form:{
                nip : '',
                kode_jabatan : null
            },
            opsiJabatan:[]
        }
    },
    methods:{
        showWindow(){
            this.$refs['pltWindow'].show();
        },        
        hideWindow(){
            this.resetForm();
            this.$refs['pltWindow'].hide();
        },
        submitPlt(){
            axios.post('/admin/action/add-plt',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_add_plt':    m="Berhasil menambah PLT!";
                                                eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                this.hideWindow();
                                                break;
                    case 'fail_add_plt_try_caught':m='Gagal menambah PLT! (Error Query/DB)';break;
                    case 'fail_add_plt_exist':m='PLT dengan data tersebut sudah ada!';break;
                    default:m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(err=>{
                alert('Gagal menambah PLT! '+err);
            });
        },

        resetForm(){
            this.nip = '';
            this.kode_jabatan = null;
        }
    }
}

</script>
