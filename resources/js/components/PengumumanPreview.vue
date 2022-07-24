<template>
    <div class="col-md-12 mt-4" id="pengumuman-preview" v-if="this.previewShown">
        <div class="card bg-secondary shadow border-0 xl-4">
            <div class="card-header">
                <span><b><i><small class="muted">PREVIEW</small></i></b></span>
                <button type="button" class="close" data-dismiss="#pengumuman-preview" aria-label="Close" @click="previewShown = false">
                            <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body" v-html="this.previewContent">
                {{ this.previewContent }}
            </div>
        </div>
    </div>
</template>

<script>

import eventbus from "../eventbus"

    export default{

        props:{
            shown :{
                type:Boolean,
                default:false
            },
            content :{
                type:String,
                default:""
            }
        },

        data(){
            return{
                previewShown : this.shown,
                previewContent : this.content
            }
        },

        mounted(){
            console.log('preview is mounted')
            eventbus.$on('previewCallback', (payload)=>{
                // console.log("previewCallback");
                this.previewContent = payload.isi
                this.previewShown = true
            })
        },

    }
</script>
