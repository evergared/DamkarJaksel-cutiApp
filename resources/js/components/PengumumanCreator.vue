<template>
    <div class="col-md-12">
        <div class="card bg-secondary shadow border-0 xl-4">
            <div class="card-header" v-if="!um">
                <h1>Buat Pengumuman Baru</h1>
                <small tabindex="-1">Buat post baru yang akan tampil sebagai pengumuman di halaman utama. Pengumuman yang dibuat tidak langsung tampil, pindah ke <a :href="'/pengumuman/list'"><u>daftar postingan</u></a> untuk mengatur tampilan halaman utama.</small>
            </div>
            <div class="card-header" v-else>
                <h1>Ubah Pengumuman</h1>
            </div>            
            <div class="card-body">


                    <div class="alert alert-success alert-dismissible fade show focus" role="alert" v-if="this.addSuccess">
                        <span class="alert-inner--icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="alert-inner--text">{{ messageSuccess }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="addSuccess = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                
                    <div class="alert alert-warning alert-dismissible fade show focus" role="alert" v-if="addFail">
                        <span class="alert-inner--icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="alert-inner--text">{{ messageFail }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="addFail = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" id="judul" aria-describedby="judul-desc" v-model="pengumumanData.judul" autocomplete="off">
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="judulError">
                                <strong>{{ judulErrorMessage }}</strong>
                            </span>                        
                        <small id="judul-desc" tabindex="-1">Judul hanya sebagai pembeda di database dan tidak akan ditampilkan saat diterapkan.</small>
                    </div>
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Pengumuman</label>
                        <ckeditor :editor="editor" v-model="pengumumanData.isi"></ckeditor>
                    </div>
                    <button type="button" id="submit-btn" class="btn btn-primary" :disabled="this.bSubmit" v-if="!um">Submit</button>
                    <button type="button" id="update-btn" class="btn btn-primary" :disabled="this.bSubmit" v-else>Update</button>
                    <button type="button" id="preview-btn" class="btn btn-secondary" :disabled="this.bPreview" @click="previewPengumuman()">Preview</button>
                
            </div>
        </div>
    </div>    
</template>

<script>

import eventbus from "../eventbus"
import CKEditor from "@ckeditor/ckeditor5-vue2"


export default{

    props:{
        updateMode : {
            type : Boolean,
            default:false
        },
        judul :{
            type:String,
            default:""
        },
        slug : {
            type:String,
            default:""
        },
        isi : {
            type:String,
            default:""
        }
    },
    
    components:{
        ckeditor: CKEditor.component
    },

    data(){
        return{

            um : this.updateMode,


            addSuccess : false,
            messageSuccess : "Contoh pesan sukses",
            addFail : false,
            messageFail : "Contoh pesan gagal",

            judulError : false,
            judulErrorMessage : "Contoh pesan error pada judul",

            bSubmit : true,
            bUpdate : true,
            bPreview : true,

            pengumumanData :{
            judul : this.judul,
            slug : this.slug,
            isi : this.isi,                
            },

            pengumumanDefault :{
            judul : this.judul,
            slug : this.slug,
            isi : this.isi,                
            },            

            editor : ClassicEditor
        }
    },

    watch:{
        pengumumanData:{
            handler(val){
                if((val.judul == this.pengumumanDefault.judul) && (val.isi == this.pengumumanDefault.isi))
                {
                    this.bSubmit = true
                    this.bUpdate = true
                    this.bPreview = true
                }
                else
                {
                    this.bSubmit = false
                    this.bUpdate = false
                    this.bPreview = false
                }
            },
            deep:true
        }
    },

    methods:{
        previewPengumuman(){

            eventbus.$emit('previewCallback', this.pengumumanData)

        }
    }
    
}
</script>
