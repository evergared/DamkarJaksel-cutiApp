<template>
    <div>
        <button class="btn btn-sm btn-primary" @click="callApprovalWindow">
             <slot>Delete</slot>
        </button>

        <b-modal ref="aw" hide-header>
            <b-form-radio-group>
                <b-form-radio v-model="approval.status" value='s'>Setujui</b-form-radio>
                <b-form-radio v-model="approval.status" value='t'>Tangguhkan</b-form-radio>
                <b-form-radio v-model="approval.status" value='u'>Perubahan</b-form-radio>
                <b-form-radio v-model="approval.status" value='x'>Tolak</b-form-radio>
            </b-form-radio-group>
            <br>
            <b-form-input v-model="approval.keterangan" placeholder="Alasan persetujuan"></b-form-input>
            <template #modal-footer="{}">
                <button type="button" class="btn btn-primary" @click="updateApproval()">Ubah approval</button>
                <button type="button" class="btn btn-secondary" @click="hideApprovalWindow()">Batal</button>
            </template>
        </b-modal>

    </div>
</template>

<script>
import 'bootstrap-vue'
import axios from 'axios'
    export default{
        props: ['nip', 'no_cuti'],
        data(){
            return{
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
                axios.get('/data-cuti/approval/fetch')
                .then(resp =>{
                    this.approval.status = resp.status;
                    this.approval.keterangan = resp.keterangan;
                    this.$refs['aw'].show();
                })
                .catch(err => {
                    console.log('Fetch approval status error on '+this.nip+' no cuti '+this.no_cuti);
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