<template>
    
    <div>
        <button class="btn btn-primary" @click="deleteUser">
            <slot>Delete</slot>
        </button>
        <b-modal ref="test">
            test {{this.nama}}
        </b-modal>
    </div>
</template>

<script>
import 'bootstrap-vue'

    export default{
        props: ['nip', 'nama'],
        methods: {
            deleteUser() {
                alert(`I am ${this.nama}!`)
                this.$refs['test'].show();
            }
        },
    }
</script>