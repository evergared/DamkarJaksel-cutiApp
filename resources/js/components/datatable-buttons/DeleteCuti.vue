<template>
    <button class="btn btn-sm btn-danger" @click="hapusCuti">
        <slot>Hapus Cuti</slot>
    </button>
</template>

<script>
import axios from 'axios'
import eventbus from '../../eventbus'
    export default{
        props: ['nip', 'no_cuti'],
        data() {
            return{
                hapus:{
                    nip:this.nip,
                    no_cuti:this.no_cuti
                }
            }         
        },
        methods: {
            hapusCuti() {
                axios.post('/data-cuti/delete',this.hapus)
                .then(resp => {
                    var m;

                    switch(resp.data)
                    {
                        case 'delete_success' : m = "Berhasil dihapus!";eventbus.$emit('draw',{message:"memuat ulang. . ."});break;
                        case 'delete_fail' : m = "Gagal dihapus!";break;
                        default: 'Unknown error : '+resp.data;break;
                    }

                    alert(m);

                })
                .catch(err => {
                    console.log("Delete Cuti error : "+err);
                    alert("Gagal membuat sambungan ke server!");
                })

                
            }
        },
    }
</script>