<template>
    <div>
        <b-modal>
            test
        </b-modal>
    </div>
</template>

<script lang="ts">
import 'bootstrap-vue'

export default{
    props:['nip','no_cuti'],
    data(){
        return{
            approval:{
                persetujuan:'',
                keterangan:''
            }
        }
    },
    methods:{

    }
}
</script>