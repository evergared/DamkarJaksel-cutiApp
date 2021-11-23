<template>
    <div>
        <button class="btn btn-sm btn-primary" @click="callApprovalWindow">
             <slot>Ubah Persetujuan</slot>
        </button>

        <b-modal size='sm' ref="approval-window" hide-header hide-backdrop>
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
                options:[
                    {text:"Setujui",value:"s"},
                    {text:"Tangguhkan",value:"t"},
                    {text:"Ubah",value:"u"},
                    {text:"Tolak",value:"x"},
                ]
            }
        },
        methods: {
            callApprovalWindow() {
                axios.post('/data-cuti/approval/fetch',this.cuti)
                .then(resp =>{
                    this.approval.status = resp.data.approval;
                    this.approval.keterangan = resp.data.keterangan;
                    this.$refs['approval-window'].show();
                    // $('#'+this.windowId).modal('show');
                })
                .catch(err => {
                    console.log('Fetch approval status error on '+this.nip+' no cuti '+this.no_cuti+' error : '+err);
                    alert('Gagal mengambil data approval cuti!');
                })
            },
            updateApproval(){
                axios.post('/data-cuti/approval/action',this.approval)
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