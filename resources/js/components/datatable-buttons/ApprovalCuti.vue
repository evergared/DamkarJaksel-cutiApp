<template>
    <div>
        <button class="btn btn-sm btn-primary" @click="callApprovalWindow">
             <slot>Ubah Persetujuan</slot>
        </button>

        <b-modal size='xl' ref="approval-window" hide-footer hide-header>
            test
        </b-modal>
    </div>
</template>

<script>

import axios from 'axios'
import 'bootstrap-vue'
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
                }
            }
        },
        methods: {
            callApprovalWindow() {
                axios.post('/data-cuti/approval/fetch',this.cuti)
                .then(resp =>{
                    console.log('data : '+resp.data.approval);
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
                        case 'approval_update_success': m='Berhasil mengganti persetujuan!';break;
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
                    this.$refs['aw'].hide();
            }
        },
    }
</script>