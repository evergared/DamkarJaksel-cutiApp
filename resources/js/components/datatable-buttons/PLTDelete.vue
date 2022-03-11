<template>
    <div>
        <button class="btn btn-sm btn-danger" @click="deletePLT">
            <slot>Hapus Jabatan PLT</slot>
        </button>
    </div>
</template>

<script>
import bootstrap from 'bootstrap-vue'
import axios from 'axios'
import eventbus from '../../eventbus'

Vue.use(bootstrap);

export default{
    props:['nip','kode_jabatan'],
    data(){
        return{
            user:{
                nip:this.nip,
                kode_jabatan:this.kode_jabatan,
            }
        }
    },
    methods:{
        deletePLT(){

            if(confirm('Hapus Jabatan PLT ?'))
                axios.post(`/admin/action/delete-plt`,this.user)
                .then(resp =>{

                    var m;
                    switch(resp.data)
                    {
                        case "success_delete_plt":m="Sukses hapus jabatan PLT!";eventbus.$emit('draw',{message:'Memuat...'});break;
                        case "fail_delete_plt_exist":m="Jabatan PLT tidak ditemukan!";break;
                        case "fail_delete_plt_try_caught":m="Gagal hapus PLT! (query/db error)";break;
                        default: m='Unknown Error';break;
                    }

                    alert(m);
                })
                .catch(err => {
                    alert('Gagal hapus jabatan PLT! '+err);
                })
        }
    }
}
</script>
