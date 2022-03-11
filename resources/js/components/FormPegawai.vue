<template>
    <div class="col card shadow border-0 xl-12">
        <h2 v-if="!um">Form Tambah Data Pegawai</h2>
        <h2 v-else>Form Update Data Pegawai</h2>

        <form @submit.prevent = 'submitPegawai' @reset='resetForm'>

                <b-form-group
                    id="input-form-nip"
                    label="NIP Pegawai : "
                    label-for="form-nip">
                    <b-input-group>

                        <b-form-input
                            id="form-nip"
                            v-model="form.nip"
                            :state="inputNipState"
                            :formatter="fnip"
                            aria-describedby="input-nip-feedback"
                            @change="nipState"
                            autocomplete="off"
                            required></b-form-input>

                        <b-input-group-append>
                            <b-button  variant="outline-primary" @click="cekNip()">Cek</b-button>
                        </b-input-group-append>

                        <b-form-invalid-feedback id="input-nip-feedback">
                            {{errorMessageNipState}}
                        </b-form-invalid-feedback>

                    </b-input-group>
                </b-form-group>


                <b-form-group
                    id="input-form-nrk"
                    label="NRK Pegawai : "
                    label-for="form-nrk"
                    v-if="form.golongan != 'PJLP'">
                    <b-input-group>

                        <b-form-input
                            id="form-nrk"
                            v-model="form.nrk"
                            :state="inputNrkState"
                            :formatter="fnip"
                            aria-describedby="input-nrk-feedback"
                            @change="nrkState"
                            autocomplete="off"
                            ></b-form-input>

                        <b-input-group-append>
                            <b-button  variant="outline-primary" @click="cekNrk()">Cek</b-button>
                        </b-input-group-append>

                        <b-form-invalid-feedback id="input-live-feedback">
                            {{errorMessageNrkState}}
                        </b-form-invalid-feedback>

                    </b-input-group>
                </b-form-group>

                <b-form-group
                id="input-form-nama"
                label="Nama : "
                label-for="form-nama">
                    <b-form-input
                    id="form-nama"
                    v-model="form.nama"
                    required></b-form-input>
                </b-form-group>

                <b-form-group 
                id="input-form-pendidikan"
                label="Pendidikan : "
                label-for="form-pendidikan">
                    <b-form-select
                    id="form-pendidikan"
                    v-model="form.pendidikan"
                    :options="opsiPendidikan"
                    ></b-form-select>
                </b-form-group>

                <b-form-group 
                id="input-form-golongan"
                label="Golongan : "
                label-for="form-golongan">
                    <b-form-select
                    id="form-golongan"
                    v-model="form.golongan"
                    :options="opsiGolongan"
                    ></b-form-select>
                </b-form-group>

                <b-form-group 
                id="input-form-penempatan"
                label="Penempatan : "
                label-for="form-penempatan">
                    <b-form-select
                    id="form-jabatan"
                    v-model="form.kode_penempatan"
                    :options="opsiPenempatan"
                    ></b-form-select>
                </b-form-group>

                <b-form-group 
                id="input-form-jabatan"
                label="Jabatan : "
                label-for="form-jabatan">
                    <b-form-select
                    id="form-jabatan"
                    v-model="form.jabatan"
                    :options="opsiJabatan"
                    ></b-form-select>
                </b-form-group>                

                <b-form-group 
                id="input-form-kompi"
                label="Kompi / Jenis Shift : "
                label-for="form-kompi">
                    <b-form-select
                    id="form-kompi"
                    v-model="form.kompi"
                    :options="opsiKompi"
                    ></b-form-select>
                </b-form-group>

                <b-form-group
                id="input-form-jabket"
                label="Keterangan Jabatan : "
                label-for="form-jabket"
                description="Berpengaruh pada detil jabatan saat print lembar form cuti.">
                    <b-form-textarea
                    id="form-jabket"
                    v-model="form.jabket"
                    placeholder="Masukan detil jabatan.."
                    rows="3"
                    max-rows="6"></b-form-textarea>
                </b-form-group>

                <b-form-group
                label="Masa Kerja / Mulai Kerja : ">
                    <b-form-radio-group
                    v-model="masKerRadioStatus"
                    buttons
                    button-variant="primary">
                        <b-form-radio value="a">Gunakan Tanggal Hari Ini</b-form-radio>
                        <b-form-radio value="b">Pilih Tanggal</b-form-radio>
                        <b-form-radio value="c" v-if="updateMode">Pertahankan Data Lama</b-form-radio>
                    </b-form-radio-group>

                    <b-form-datepicker
                    class="my-3"
                    v-model="form.mas_ker"
                    :disabled="masKerState"
                    placeholder="Tanggal Masuk Kerja"
                    locale="id-ID"
                    ></b-form-datepicker>

                </b-form-group>



            <div class="my-3">
                <b-button type="submit" variant="primary">Submit</b-button>
                <b-button type="reset" variant="danger">Reset</b-button>
            </div>



        </form>

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
import 'bootstrap-vue/dist/bootstrap-vue.css';
import eventbus from '../eventbus';
import axios from 'axios';

Vue.use(BootstrapVue);

export default {
    mounted(){

        axios.get('/admin/list-jabatan')
        .then(resp => {
            this.opsiJabatan = resp.data;
            this.opsiJabatan.push({value:null,text:'Pilih Jabatan'});

        })
        .catch(err => {
            console.log('error fetch list jabatan '+err);
        });

        axios.get('/admin/list-penempatan')
        .then(r => {
            this.opsiPenempatan = r.data;
            this.opsiPenempatan.push({value:null,text:'Pilih Penempatan/Sub. Bag'});
        })
        .catch(err => {
            console.log('error fetch list penempatan '+err);
        });

        if(this.updateMode)
        {
            if(this.mas_ker != null)
                this.masKerRadioStatus = 'c';
            else
                this.masKerRadioStatus = 'a';

            this.errorMessageNipState = "Data dengan NIP tersebut tidak ada / tidak ditemukan.";
            this.errorMessageNrkState = "Data dengan NRK tersebut tidak ada / tidak ditemukan.";
            
        }
        else
        {
            this.errorMessageNipState = "Data dengan NIP tersebut sudah ada.";
            this.errorMessageNrkState = "Data dengan NRK tersebut sudah ada.";
        }

    },

    props:{
        um:{
            type: Boolean,
            default: false
        },
        nip:{type: String, default: ''},
        nrk:{type: String, default: ''},
        nama:{type:String},
        golongan:{type:String, default:null},
        jabatan:{type:String, default:null},
        kasie:{type:String, default:'-'},
        atasan:{type:String, default:'-'},
        pendidikan:{type:String, default:null},
        kodePenempatan:{type:String, default:null},
        kompi:{type:String, default: null},
        keterangan:{type:String},
        jabket:{type:String},
        mas_ker:{type:String},

    },
    data(){
        return{
            updateMode: this.um,
            form:{
                nip : this.nip,
                oldNip : this.nip,
                nrk : this.nrk,
                oldNrk : this.nrk,
                nama : this.nama,
                golongan : this.golongan,
                jabatan : this.jabatan,
                kasie : this.kasie,
                atasan : this.atasan,
                pendidikan : this.pendidikan,
                kode_penempatan : this.kodePenempatan,
                kompi : this.kompi,
                keterangan : this.keterangan,
                jabket : this.jabket,
                mas_ker : this.mas_ker,
                masKerToday : false
            },
            cekData:{
                nip:'',
                nama:'',
                golongan:'',
                jabatan:'',
                penempatan:'',
                keteranganJabatan:'',
            },
            inputNipState : null,
            tempNipState : false,
            errorMessageNipState:'',            
            inputNrkState : null,
            tempNrkState : false,
            errorMessageNrkState:'',
            masKerRadioStatus : 'a',
            opsiJabatan:[],
            opsiPenempatan:[],
            opsiGolongan:[
                {value: null, text : 'Pilih Golongan'},
                'PJLP',
                '(I/a)',
                '(I/b)',
                '(I/c)',
                '(I/d)',
                '(II/a)',
                '(II/b)',
                '(II/c)',
                '(II/d)',
                '(III/a)',
                '(III/b)',
                '(III/c)',
                '(III/d)',
                '(IV/a)',
                '(IV/b)',
                '(IV/c)',
                '(IV/d)',
            ],
            opsiPendidikan:[
                {value : null, text : 'Pilih Pendidikan'},
                'SD',
                'SMP',
                'SMA',
                'D3',
                'S1',
                'S2',
                'S3'
            ],
            opsiKompi:[
                {value : null, text: 'Pilih Kompi / Jenis Shift'},
                {value : 'A', text: 'Ambon'},
                {value : 'B', text: 'Bandung'},
                {value : 'C', text: 'Cepu'},
                {value : 'K', text: 'Kepala Seksi Sektor'},
                {value : 'S', text: 'Pegawai Staff'},
            ]
        }
    },
    computed:{
        masKerState(){
            if(this.masKerRadioStatus == "a" || this.masKerRadioStatus == "c")
                return true;
            else
                return false;
        },
        nipState()
        {
            if(!this.updateMode)
            {
                axios.post('admin/action/verify-nip',{assignedNip : this.form.nip})
                .then(r => {
                    if(this.form.nip.length<1)
                        this.makeNipStateNull();
                    else{

                        if(r.data == 1)
                            this.makeNipStateFalse();
                        else
                            this.makeNipStateTrue();
                    }
                })
                .catch(e =>{
                    this.makeNipStateNull();
                });
            }
            else
            {
                axios.post('admin/action/verify-nip',{assignedNip : this.form.nip})
                .then(r => {
                    if(this.form.nip.length<3)
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
            }
        },

        nrkState()
        {
            if(!this.updateMode)
            {
                axios.post('admin/action/verify-nrk',{assignedNip : this.form.nrk})
                .then(r => {
                    if(this.form.nrk.length<1)
                        this.makeNrkStateNull();
                    else{

                        if(r.data == 1)
                            this.makeNrkStateFalse();
                        else
                            this.makeNrkStateTrue();
                    }
                })
                .catch(e =>{
                    this.makeNrkStateFalse();
                });
            }            
            else
            {
                axios.post('admin/action/verify-nrk',{assignedNip : this.form.nrk})
                .then(r => {
                    if(this.form.nrk.length<1)
                        this.makeNrkStateNull();
                    else{

                        if(r.data == 1)
                            this.makeNrkStateTrue();
                        else
                            this.makeNrkStateFalse();
                    }
                })
                .catch(e =>{
                    this.makeNrkStateFalse();
                });
            }
        },

        fnip(value){
            if(/^(\d|[+-])+$/.test(value))
            return value;
        }

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

        makeNrkStateTrue(){
            this.inputNrkState = true;
        },

        makeNrkStateFalse(){
            this.inputNrkState = false;
        },
        makeNrkStateNull(){
            this.inputNrkState = null;
        },

        makeTempNrkStateTrue(){
            this.tempNrkState = true;
        },

        makeTempNrkStateFalse(){
            this.tempNrkState = false;
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
                        this.openCekDataWindow('/admin/action/f',this.form.nip)
                        
                    }
                    else
                        alert('NIP Tidak ditemukan!')
                
            })
            .catch(e =>{
                this.makeTempNipStateFalse();
            });
        },

        cekNrk(){

            if(this.form.nrk.length < 1)
            {
                alert('NRK Masih Kosong!');
                return;
            }


            axios.post('admin/action/verify-nrk',{assignedNip : this.form.nrk})
            .then(r => {

                    if(r.data == 1)
                        this.makeTempNrkStateTrue();
                    else
                        this.makeTempNrkStateFalse();

                    if(this.tempNrkState)
                    {
                        this.openCekDataWindow('/admin/action/fn',this.form.nrk)
                    }
                    else
                        alert('NRK Tidak ditemukan!')
                
            })
            .catch(e =>{
                this.makeTempNrkStateFalse();
            });
        },

        openCekDataWindow(link, item)
        {
            axios.post(link ,{assignedNip : item})
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


        submitPegawai(){


            if(this.masKerRadioStatus == "a")
                this.form.masKerToday = true;

            if(this.um)
            {
                axios.post('/admin/action/update-pegawai',this.form)
                .then(resp=>{
                    var m;

                    switch(resp.data){
                        case 'success_update_pegawai':    m="Berhasil update Pegawai!";
                                                    eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                    eventbus.$emit('pegawai-update-finish');
                                                    this.resetForm();
                                                    break;
                        case 'fail_update_pegawai_try_caught':m='Gagal menambah Pegawai! (Error Query/DB)';break;
                        default:m='Unknown Error';break;
                    }

                    alert(m);
                })
                .catch(err=>{
                    alert('Gagal menambah Pegawai! '+err);
                });
            }
            else
            {
                axios.post('/admin/action/add-pegawai',this.form)
                .then(resp=>{
                    var m;

                    switch(resp.data){
                        case 'success_add_pegawai':    m="Berhasil menambah Pegawai!";
                                                    eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                    this.resetForm();
                                                    break;
                        case 'fail_add_pegawai_try_caught':m='Gagal menambah Pegawai! (Error Query/DB)';break;
                        case 'fail_add_pegawai_exist':m='Pegawai dengan data tersebut sudah ada!';break;
                        default:m='Unknown Error';break;
                    }

                    alert(m);
                })
                .catch(err=>{
                    alert('Gagal menambah Pegawai! '+err);
                });

                // alert(JSON.stringify(this.form));
                // console.log(this.form)
            }


        },

        resetForm(){
            this.nip = '';
            this.kode_jabatan = null;
        }
    }
}

</script>
