<template>
    <div>
        <button class="btn btn-sm btn-danger" @click="deletePenempatan">
            <slot>Hapus Data Penempatan</slot>
        </button>
    </div>
</template>

<script>
import bootstrap from 'bootstrap-vue'
import axios from 'axios'
import eventbus from '../../eventbus'

Vue.use(bootstrap);

export default{
    props:['kode_panggil'],
    data(){
        return{
            dt:{
                kode_panggil : this.kode_panggil
            }
        }
    },
    methods:{
        deletePenempatan(){

            if(confirm('Hapus Data ?'))
                axios.post(`/admin/action/delete-penempatan`,this.dt)
                .then(resp =>{

                    var m;
                    switch(resp.data)
                    {
                        case "success_delete_penempatan":m="Sukses hapus data!";eventbus.$emit('draw',{message:'Memuat...'});break;
                        case "fail_delete_penempatan_exist":m="Data tidak ditemukan!";break;
                        case "fail_delete_penempatan_try_caught":m="Gagal hapus data! (query/db error)";break;
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
