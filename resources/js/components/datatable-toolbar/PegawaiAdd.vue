<template>
<div>
    <button class="btn btn-primary" type="button" @click="showWindow">
        <i class="fa-regular fa-file-circle-plus"></i>
        <span>Tambah Pegawai</span>
    </button>

    <b-modal size='lg' ref="pegawaiWindow" hide-header hide-backdrop hide-footer>

        <form-pegawai></form-pegawai>

    </b-modal>

</div>
</template>
<script>

import formPegawai from '../FormPegawai.vue';

import BootstrapVue from 'bootstrap-vue';
import axios from 'axios';
import eventbus from '../../eventbus';

Vue.use(BootstrapVue);

export default {

    props:[
        'nip',
        'nrk',
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
    methods:{
        showWindow(){
            this.$refs['pegawaiWindow'].show();
        },        
    }
}

</script>
