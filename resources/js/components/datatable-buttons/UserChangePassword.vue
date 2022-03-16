<template>
    <div>
        <button class="btn btn-sm btn-primary" @click="callWindow">
            <slot>Ganti Password</slot>
        </button>

        <b-modal ref='window' size="sm" hide-backdrop>
            <template #modal-header>
                Ganti Password
            </template>

            <label for="#input">Password Baru</label>
            <input type="text" v-model="user.pass" autofocus>

            <template #modal-footer>
                <b-button
                    variant="primary"
                    size="md"
                    @click="changePassword"
                >
                Submit
                </b-button>

                <b-button
                    variant="secondary"
                    size="md"
                    @click="closeWindow"
                >
                Batal
                </b-button>
            </template>
        </b-modal>

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
                pass:''
            }
        }
    },
    methods:{
        callWindow(){
            console.log('clicked');
            this.$refs['window'].show();
        },
        closeWindow(){
            this.$refs['window'].hide();
        },
        changePassword(){
            console.log('hit change password nip : '+this.user.nip+' pass : '+this.user.pass);
            axios.patch(`/user/action/change-password`,this.user)
            .then(resp =>{

                var m;
                switch(resp.data)
                {
                    case "success_change_password":m="Sukses ganti password!";break;
                    case "fail_change_password_try_caught":m="Gagal ganti password! (query/db error)";break;
                    default: m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(err => {
                alert('Gagal ganti password! '+err);
            })
        }
    }
}
</script>
