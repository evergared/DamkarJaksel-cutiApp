<template>
    <div>
        <button class="btn btn-sm btn-primary" @click="callApprovalWindow">
             <slot>Ubah Persetujuan</slot>
        </button>

        <b-modal size='md' ref="approval-window" hide-header hide-backdrop>
            <h2>Data Persetujuan</h2>

            <div class="mb-3" v-if="data_persetujuan.s_kasie">
                <strong>Persetujuan Kasie</strong><br>
                <small><strong>Status : </strong>{{data_persetujuan.a_kasie}}</small> <br>
                <small><strong>Keterangan : </strong>{{data_persetujuan.k_kasie}}</small><br>
            </div>

            <div class="mb-3" v-if="data_persetujuan.s_tu">
                <strong>Persetujuan TU</strong><br>
                <small><strong>Status : </strong>{{data_persetujuan.a_tu}}</small><br>
                <small><strong>Keterangan : </strong>{{data_persetujuan.k_tu}}</small><br>
            </div>

            <div class="mb-3" v-if="data_persetujuan.s_ppk">
                <strong>Persetujuan PPK</strong><br>
                <small><strong>Status : </strong>{{data_persetujuan.a_ppk}}</small><br>
                <small><strong>Keterangan : </strong>{{data_persetujuan.k_ppk}}</small><br>
            </div>

            <h3>Ubah Persetujuan Anda</h3>
            <b-form-radio-group
            v-model="approval.status"
            :options="options"
            stacked>
            </b-form-radio-group>
            <br>
            <b-input v-model="approval.keterangan" placeholder="Keterangan"></b-input>
            <template #modal-footer="{}">
                <button type="button" class="btn btn-primary" @click="updateApproval()">Update</button>
                <button type="button" class="btn btn-secondary" @click="hideApprovalWindow()">Batal</button>
            </template>
        </b-modal>
    </div>
</template>

<script>

import axios from 'axios';
import BootstrapVue from 'bootstrap-vue';
import eventbus from '../../eventbus';

Vue.use(BootstrapVue);

export default{
        props: ['DT_RowIndex','nip', 'no_cuti'],
        data(){
            return{
                windowId:'approval-window-'+this.DT_RowIndex,
                cuti:{
                    nip:this.nip,
                    no_cuti:this.no_cuti
                },
                approval:{
                    nip:this.nip,
                    no_cuti:this.no_cuti,
                    status:'',
                    keterangan:''
                },
                data_persetujuan:{
                    a_kasie:'',
                    k_kasie:'',
                    s_kasie:false,
                    a_tu:'',
                    k_tu:'',
                    s_tu:false,
                    a_ppk:'',
                    k_ppk:'',
                    s_ppk:false},
                options:[
                    {text:"Setujui",value:"s"},
                    {text:"Tangguhkan",value:"t"},
                    {text:"Ubah",value:"u"},
                    {text:"Tolak",value:"x"},
                ]
            }
        },
        methods: {
            getStatus(value){
                var st = '';
                switch(value){
                    case 's': st = 'Disetujui';break;
                    case 't': st = 'Ditangguhkan';break;
                    case 'u': st = 'Perubahan';break;
                    case 'x': st = 'Ditolak';break;
                    case '-': st = 'Belum Dilihat';break;
                    default : st = '';break;
                }
                return st;
            },
            callApprovalWindow() {
                // TODO : fix v-if on approval-window
                axios.post('/data-cuti/approval/fetch-plt',this.cuti)
                .then(resp =>{
                    this.data_persetujuan.a_kasie = this.getStatus(resp.data.a_kasie);
                    this.data_persetujuan.k_kasie = resp.data.k_kasie;
                    this.data_persetujuan.s_kasie = resp.data.s_kasie;
                    this.data_persetujuan.a_tu = this.getStatus(resp.data.a_tu);
                    this.data_persetujuan.k_tu = resp.data.k_tu;
                    this.data_persetujuan.s_tu = resp.data.s_tu;
                    this.data_persetujuan.a_ppk = this.getStatus(resp.data.a_ppk);
                    this.data_persetujuan.k_ppk = resp.data.k_ppk;
                    this.data_persetujuan.s_ppk = resp.data.s_ppk;

                    this.$refs['approval-window'].show();
                })
                .catch(err => {
                    console.log('Fetch approval status error on '+this.nip+' no cuti '+this.no_cuti+' error : '+err);
                    alert('Gagal mengambil data approval cuti!');
                })
            },
            updateApproval(){
                axios.post('/data-cuti/approval/plt-action',this.approval)
                .then(resp => {
                    var m;
                    switch(resp.data)
                    {
                        case 'approval_update_success': m='Berhasil mengganti persetujuan!';eventbus.$emit('draw',{message:"Memuat..."});this.hideApprovalWindow();break;
                        case 'approval_update_try_caught':m='Gagal mengganti persetujuan!';break;
                        default:m='Unknown Error';break;
                    }
                    alert(m);
                })
                .catch(err => {
                    alert('Gagal membuat koneksi ke server!');
                })
            },
            hideApprovalWindow(){
                    this.$refs['approval-window'].hide();
            }
        },
    }
</script>