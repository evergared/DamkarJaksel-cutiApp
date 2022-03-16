<template>
    <div>
        <button class="btn btn-sm btn-danger" @click="deleteJabatan">
            <slot>Hapus Data</slot>
        </button>
    </div>
</template>

<script>
import bootstrap from 'bootstrap-vue'
import axios from 'axios'
import eventbus from '../../eventbus'

Vue.use(bootstrap);

export default{
    props:['no'],
    data(){
        return{
            dt:{
                no : this.no
            }
        }
    },
    methods:{
        deleteJabatan(){

            if(confirm('Hapus Data ?'))
                axios.post(`/admin/action/delete-jabatan`,this.dt)
                .then(resp =>{

                    var m;
                    switch(resp.data)
                    {
                        case "success_delete_jabatan":m="Sukses hapus data!";eventbus.$emit('draw',{message:'Memuat...'});break;
                        case "fail_delete_jabatan_exist":m="Data tidak ditemukan!";break;
                        case "fail_delete_jabatan_try_caught":m="Gagal hapus data! (query/db error)";break;
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
