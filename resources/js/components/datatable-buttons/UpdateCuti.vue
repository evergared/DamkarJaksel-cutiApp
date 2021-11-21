<template>
    <div>
        <button class="btn btn-sm btn-secondary" @click='callUpdateWindow'>
            <slot>Update Data Cuti</slot>
        </button>

        <b-modal size='xl' ref="uw" hide-footer hide-header>
            <form-cuti 
            :index='this.DT_RowIndex'
            :nip='this.nip'
            :no_cuti='this.no_cuti'
            :tgl_awal='this.tgl_awal'
            :tgl_akhir='this.tgl_akhir'
            :total_cuti='this.total_cuti'
            :jenis_cuti='this.jenis_cuti'
            :sisa='this.sisa'
            :alasan='this.alasan'
            :alamat='this.alamat'
            um='true'></form-cuti>
        </b-modal>

        <b-modal size='lg' ref="cw" hide-header>
            Perhitungan jumlah cuti yang baru : {{updateData.jumlahHari}} hari (maks : {{updateData.sisa}})<br>
            <span v-if="updateData.jumlahHari > updateData.sisa"><small>Hanya {{updateData.sisa}} hari yang diterima dari {{updateData.jumlahHari}} hari yang diajukan. Terhitung mulai dari tanggal awal ({{updateData.tgl_awal}})</small></span><br><br>
            Berikut ini ajuan tanggal cuti baru yang diterima : <br><br>
            <ol>
                <li v-for="tgl in updateData.tanggal" :key="tgl"><small>{{ tgl }}</small></li>
            </ol>
            <template #modal-footer="{}">
                <button type="button" class="btn btn-primary" @click="requestUpdate()">Setuju dan Update</button>
                <button type="button" class="btn btn-secondary" @click="hideConfirmationWindow()">Batal</button>
            </template>
        </b-modal>
    </div>
</template>


<script>
import formCuti from '../FormCuti'
import eventbus from '../../eventbus'
import 'bootstrap-vue'

    export default{
        props: [
            'DT_RowIndex',
            'nip',
            'nama',
            'no_cuti',
            'alasan',
            'tgl_awal',
            'tgl_akhir',
            'total_cuti',
            'jenis_cuti',
            'sisa',
            'alamat'
        ],
        components:{
            formCuti
        },
        data(){
            return{
                    updateData:{
                        tgl_awal:'',
                        tgl_akhir:'',
                        total_cuti:0,
                        sisa:0,
                        jenis_cuti:'',
                        alamat:'',
                        alasan:'',
                        jumlahHari:'',
                        tanggal:[]
                }
            
            }
        },
        methods: {
            callUpdateWindow() {
                    this.$refs['uw'].show();
            },
            hideConfirmationWindow(){
                this.$refs['cw'].hide();
            },
            requestUpdate(){
                eventbus.$emit('cuti-update-confirm-'+this.DT_RowIndex);
            },
            handleUpdateSuccess(){
                this.$refs['uw'].hide();
                this.$refs['cw'].hide();
                eventbus.$emit('draw',{message:'Memuat . . .'});
            }
        },
        mounted(){
                eventbus.$on('cuti-update-callback-'+this.DT_RowIndex, (payload) =>{
                    this.updateData.tgl_awal = payload.start; 
                    this.updateData.tgl_akhir = payload.end; 
                    this.updateData.total_cuti= payload.lama;
                    this.updateData.jumlahHari=payload.jumlahHari;
                    this.updateData.jenis_cuti= payload.jenis_cuti;
                    this.updateData.sisa = payload.batashari;
                    this.updateData.alamat= payload.alamat;
                    this.updateData.alasan = payload.alasan;
                    this.updateData.tanggal = payload.tanggal;

                    this.$refs['cw'].show();
                });

                eventbus.$on('cuti-update-success-'+this.DT_RowIndex, (payload) =>{
                    this.handleUpdateSuccess();
                })
        }        
    }
</script>