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

                <b-input-group>
                    <b-form-input
                    id="input-nip"
                    v-model="form.nip"
                    :state='inputNipState'
                    @change="nipState"
                    aria-describedby="input-nip-feedback"
                    required></b-form-input>

                    <b-input-group-append>
                        <b-button  variant="outline-primary" @click="cekNip()">Cek</b-button>
                    </b-input-group-append>

                    <b-form-invalid-feedback id="input-nip-feedback">
                            Nip tidak ditemukan!
                    </b-form-invalid-feedback>

                </b-input-group>

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

        <b-modal ref="cekDataWindow" size="sm" hide-backdrop hide-footer hide-header>

            <h2>Cek Data Pegawai</h2>

            <strong>NIP : </strong>{{cekData.nip}} <br>
            <strong>NAMA : </strong>{{cekData.nama}} <br>
            <strong>GOLONGAN : </strong>{{cekData.golongan}} <br>
            <strong>JABATAN : </strong>{{cekData.jabatan}} <br>
            <strong>PENEMPATAN : </strong>{{cekData.penempatan}} <br>
            <strong>KETERANGAN JABATAN : </strong>{{cekData.keteranganJabatan}} <br>

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
            cekData:{
                nip:'',
                nama:'',
                golongan:'',
                jabatan:'',
                penempatan:'',
                keteranganJabatan:'',
            },
            opsiJabatan:[],
            inputNipState :null,
            tempNipState:null
        }
    },
    computed:{
        nipState()
            {
                    axios.post('admin/action/verify-nip',{assignedNip : this.form.nip})
                    .then(r => {
                        if(this.form.nip.length<1)
                            this.makeNipStateNull();
                        else{

                            if(r.data == 1)
                                this.makeNipStateTrue();
                            else
                                this.makeNipStateFalse();
                        }
                    })
                    .catch(e =>{
                        this.makeNipStateNull();
                    });
                
            },
    },
    methods:{

        makeNipStateTrue(){
            this.inputNipState = true;
        },

        makeNipStateFalse(){
            this.inputNipState = false;
        },
        makeNipStateNull(){
            this.inputNipState = null;
        },

        makeTempNipStateTrue(){
            this.tempNipState = true;
        },

        makeTempNipStateFalse(){
            this.tempNipState = false;
        },

        showWindow(){
            this.$refs['pltWindow'].show();
        },        
        hideWindow(){
            this.resetForm();
            this.$refs['pltWindow'].hide();
        },

        cekNip(){

            if(this.form.nip.length < 1)
            {
                alert('NIP Masih Kosong!');
                return;
            }

            axios.post('admin/action/verify-nip',{assignedNip : this.form.nip})
            .then(r => {

                    if(r.data == 1)
                        this.makeTempNipStateTrue();
                    else
                        this.makeTempNipStateFalse();

                    if(this.tempNipState)
                    {
                        this.openCekDataWindow()
                        
                    }
                    else
                        alert('NIP Tidak ditemukan!')
                
            })
            .catch(e =>{
                this.makeTempNipStateFalse();
            });
        },

        openCekDataWindow(){
            
            axios.post('/admin/action/f' ,{assignedNip : this.form.nip})
            .then(r => {

                this.cekData.nip = r.data[0].nip;
                this.cekData.nama = r.data[0].nama;
                this.cekData.golongan = r.data[0].golongan;
                this.cekData.jabatan = r.data[0].jabatan;
                this.cekData.penempatan = r.data[0].penempatan;
                this.cekData.keteranganJabatan = r.data[0].jabket;

                this.$refs['cekDataWindow'].show();

            })
            .catch(e =>{
                alert('terjadi error : '+e);
            });
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
