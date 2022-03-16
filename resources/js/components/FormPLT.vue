<template>
    <div class="col card shadow border-0 xl-12">
        <h2>Form Tambah Pelaksana Tugas</h2>

        <form @submit.prevent = 'submitPlt' @reset='resetForm'>

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
                :options="opsiPenempatan"
                ></b-form-select>
            </b-form-group>

            <b-button type="submit" variant="primary">Submit</b-button>
            <b-button type="reset" variant="danger">Reset</b-button>

        </form>

    </div>
</template>
<script>

import BootstrapVue from 'bootstrap-vue';
import eventbus from '../eventbus';
import axios from 'axios';

Vue.use(BootstrapVue);

export default {
    mounted(){
        axios.get('/admin/list-jabatan')
        .then(resp => {
            this.opsiJabatan = resp.data;
        })
        .catch(err => {
            console.log('error fetch list jabatan '+err);
        });
        this.opsiJabatan.push({value:null,text:'Pilih Jabatan'});
    },

    data(){
        return{
            form:{
                nip : '',
                kode_jabatan : ''
            },
            opsiJabatan:[]
        }
    },
    methods:{
        submitPlt(){
            axios.post('/admin/action/add-plt',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_add_plt':    m="Berhasil menambah PLT!";
                                                eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                this.resetForm();
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
