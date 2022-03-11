<template>
    <div>
        <button class="btn btn-sm btn-primary" @click="updatePegawai">
            <slot>Ubah Data Pegawai</slot>
        </button>

    <b-modal size='lg' ref="pegawaiWindow" v-model="deliberateToggle" hide-header hide-backdrop hide-footer>

        <form-pegawai
        um="true"
        :nip="this.nip"
        :nrk="this.nrk"
        :nama="this.nama"
        :golongan="this.golongan"
        :jabatan="this.kode_jabatan"
        :kasie="this.kasie"
        :atasan="this.atasan"
        :pendidikan="this.pendidikan"
        :kodePenempatan="this.kode_penempatan"
        :kompi="this.kompi"
        :keterangan="this.keterangan"
        :jabket="this.jabket"
        :mas_ker="this.mas_ker"></form-pegawai>

    </b-modal>

    </div>
</template>

<script>
import formPegawai from '../FormPegawai.vue'
import bootstrap from 'bootstrap-vue'
import eventbus from '../../eventbus'
import 'bootstrap-vue'

Vue.use(bootstrap);

export default{
        props:[
        'nip',
        'nrk',
        'nama',
        'golongan',
        'kode_jabatan',
        'kasie',
        'atasan',
        'pendidikan',
        'kode_penempatan',
        'kompi',
        'keterangan',
        'jabket',
        'mas_ker'
    ],
    components:{
        formPegawai
    },
    mounted(){
        
        // TODO : fix persistent window bug
        // pegawai-pjlp.js:82583 [Vue warn]: Unknown custom element: <b-modal> - did you register the component correctly? For recursive components, make sure to provide the "name" option.

        // TODO : Decode html entity character 
        
        console.log('pegawai update mounted');
        while(this.deliberateToggle)
            this.deliberateToggle = false;



        // eventbus.$on('pegawai-update-finish', (payload) =>{
        //     this.$refs['pegawaiWindow'].hide();
        //     console.log('mas ker : '+this.mas_ker);
        // })
    },
    data(){
        return{
        deliberateToggle: false
        }
    },
    methods:{
        updatePegawai(){
            this.deliberateToggle = true;
        }
    }
}
</script>
