<template>
    <div>
        <b-form @submit="submitUserForm" @reset="resetForm">

            <b-form-group
             id="input-group-nip"
             label="NIP / NRK Pegawai : "
             label-for="input-nip"
             description="Jika non pegawai, isi dengan angka acak. NIP/NRK ini akan digunakan saat login.">
                <b-form-input
                 id="input-nip"
                 v-model="form.nip"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
            id="input-group-password"
            label="Password : "
            label-for="input-password" >
                <b-form-input
                 id="input-password"
                 v-model="form.password"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
            id="input-group-email"
            label="Email : "
            label-for="input-email" >
                <b-form-input
                 id="input-email"
                 v-model="form.email"></b-form-input>
            </b-form-group>

            <b-form-group
             id="input-group-bukan-pegawai"
             label="Status Kepegawaian : "
             description="Non-pegawai untuk test, pelaksana tugas, atau memasukan user diluar data pegawai">
                <b-form-radio-group
                v-model="form.bukanPegawai"
                :options="statusKepegawaian"
                 ></b-form-radio-group>
            </b-form-group>

            <b-form-group
            id="input-group-nama"
            label="Nama : "
            label-for="input-nama" 
            v-if="form.bukanPegawai">
                <b-form-input
                 id="input-nama"
                 v-model="form.nama"
                 required></b-form-input>
            </b-form-group>

            <b-form-group 
            id="input-group-penempatan"
            label="Penempatan : "
            label-for="input-penempatan"
            v-if="form.bukanPegawai">
                <b-form-select
                v-model="form.penempatan"
                :options="opsiPenempatan"
                ></b-form-select>
            </b-form-group>

        </b-form>
    </div>
</template>
<script>
import axios from 'axios';
import bootstrap from 'bootstrap-vue'

Vue.use(bootstrap);

export default {
    mounted(){
        axios.get('/admin/list-penempatan')
        .then(resp => {
            this.opsiPenempatan = resp.data;
        })
        .catch(err => {
            console.log('error fetch list penempatan '+err);
        });
        this.opsiPenempatan.push({value:null,text:'Pilih Penempatan'});
    },
    data(){
        return{
            form:{
                nip:'',
                password:'',
                email:'',
                bukanPegawai:false,
                nama:'',
                peran:[],
                penempatan:''
            },
            statusKepegawaian:[
                {text: 'Pegawai', value:false},
                {text: 'Non-pegawai', value:true}
            ],
            opsiPenempatan:[],
        }
    }
}
</script>
