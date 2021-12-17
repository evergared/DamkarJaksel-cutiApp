<template>
    <div>
        <button class="btn btn-sm btn-danger" @click="deleteUser">
            <slot>Hapus User</slot>
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
            user:{
                nip:this.nip,
            }
        }
    },
    methods:{
        deleteUser(){

            if(confirm('Hapus user '+this.nip+' ?'))
                axios.post(`/admin/action/delete-user`,this.user)
                .then(resp =>{

                    var m;
                    switch(resp.data)
                    {
                        case "success_delete_user":m="Sukses hapus user!";eventbus.$emit('draw',{message:'Memuat...'});break;
                        case "fail_delete_user_exist":m="User tidak ditemukan!";break;
                        case "fail_delete_user_try_caught":m="Gagal hapus user! (query/db error)";break;
                        default: m='Unknown Error';break;
                    }

                    alert(m);
                })
                .catch(err => {
                    alert('Gagal hapus user! '+err);
                })
        }
    }
}
</script>
