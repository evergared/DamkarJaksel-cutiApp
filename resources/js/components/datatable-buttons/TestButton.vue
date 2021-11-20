<template>
    <button class="btn btn-primary" @click="deleteUser">
        <slot>Delete</slot>
    </button>
</template>

<script>
    export default{
        props: ['nip', 'nama'],
        methods: {
            deleteUser() {
                alert(`I am ${this.nama}!`)
            }
        },
    }
</script>