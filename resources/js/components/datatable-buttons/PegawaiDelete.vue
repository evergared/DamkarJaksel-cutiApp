<template>
    <div>
        <button class="btn btn-sm btn-danger" @click="deletepegawai">
            <slot>Hapus Data Pegawai</slot>
        </button>
    </div>
</template>

<script>
import bootstrap from 'bootstrap-vue'
import axios from 'axios'
import eventbus from '../../eventbus'

Vue.use(bootstrap);

export default{
    props:['nip'],
    data(){
        return{
            dt:{
                nip : this.nip
            }
        }
    },
    methods:{
        deletepegawai(){
            if(confirm('Hapus Data ?'))
                axios.post('/admin/action/delete-pegawai',this.dt)
                .then(resp =>{

                    var m;
                    switch(resp.data)
                    {
                        case "success_delete_pegawai":m="Sukses hapus data!";eventbus.$emit('draw',{message:'Memuat...'});break;
                        case "fail_delete_pegawai_exist":m="Data tidak ditemukan!";break;
                        case "fail_delete_pegawai_try_caught":m="Gagal hapus data! (query/db error)";break;
                        default: m='Unknown Error';break;
                    }

                    alert(m);
                })
                .catch(err => {
                    alert('Gagal hapus data! '+err);
                })
        }
    }
}
</script>
