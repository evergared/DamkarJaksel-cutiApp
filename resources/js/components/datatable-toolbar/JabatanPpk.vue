<template>
<div>
    <button class="btn btn-primary" type="button" @click="showWindow">
        <i class="fa-regular fa-file-circle-plus"></i>
        <span>Jabatan PPK/PPTK</span>
    </button>

    <b-modal size='md' ref="ppkWindow" title="Pemegang Jabatan PPK/PPTK" hide-backdrop>

        <div class="row">
            <div class="col-md-auto">
                <strong>PPK : </strong>
            </div>
            <div class="col-md-auto ml-1">
                <div class="col-sm-auto">
                    {{ppk.nama}}
                </div>
                <div class="col-sm-auto">
                    <small tabindex="-1" class="text-muted">
                        NIP : {{ppk.nip}}
                    </small>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-auto">
                <strong>PPTK : </strong>
            </div>
            <div class="col-md-auto">
                <div class="col-sm-auto">
                    {{pptk.nama}}
                </div>
                <div class="col-sm-auto">
                    <small tabindex="-1" class="text-muted">
                        NIP : {{pptk.nip}}
                    </small>
                </div>
            </div>
        </div>

        <template #modal-footer="{}">
            <button type="button" class="btn btn-secondary" @click="changePPK()">Ganti PPK</button>
            <button type="button" class="btn btn-secondary" @click="changePPTK()">Ganti PPTK</button>
        </template>
    </b-modal>
    
    <b-modal size='md' ref="assignWindow"  hide-header hide-backdrop>

        <h3>Ganti {{aw.title}}</h3>

        <div class="row">
            <div class="col">
                <b-form-group
                    id="input-assign-nip"
                    label="Masukan NIP Pegawai : "
                    label-for="assign-nip"
                    description="Kosongkan lalu submit untuk menghilangkan pemegang lama tanpa di ganti.">
                    <b-input-group>

                        <b-form-input
                            id="input-nip"
                            v-model="assignNip"
                            debounce="500"
                            :state="inputState"
                            :formatter="nip"
                            aria-describedby="input-live-feedback"
                            @change="nipState"
                            autocomplete="off"
                            size="sm"
                            required></b-form-input>

                        <b-input-group-append>
                            <b-button size="sm" variant="outline-primary" @click="selectNip()">Cek</b-button>
                        </b-input-group-append>

                        <b-form-invalid-feedback id="input-live-feedback">
                            NIP salah atau tidak ditemukan.
                        </b-form-invalid-feedback>

                    </b-input-group>
                </b-form-group>
            </div>

        </div>

        <strong>NIP : </strong>{{selected.nip}} <br>
        <strong>NAMA : </strong>{{selected.nama}} <br>
        <strong>GOLONGAN : </strong>{{selected.golongan}} <br>
        <strong>JABATAN : </strong>{{selected.jabatan}} <br>
        <strong>PENEMPATAN : </strong>{{selected.penempatan}} <br>


        <template #modal-footer="{}">
            <button type="button" class="btn btn-secondary" @click="submit()">Submit</button>
            <button type="button" class="btn btn-secondary" @click="batal()">Batal</button>
        </template>
    </b-modal>

</div>
</template>
<script>

import BootstrapVue from 'bootstrap-vue';
import axios from 'axios';
import eventbus from '../../eventbus';

Vue.use(BootstrapVue);

export default {
    mounted(){
        this.refresh();
    },

    data(){
        return{
            form:{
                nip : '',
                ppk : null
            },
            selected:{
                nip:'-',
                nama:'-',
                golongan:'-',
                jabatan:'-',
                penempatan:'-'
            },
            ppk:{
                nip :'-',
                nama : '-'
            },
            pptk:{
                nip :'-',
                nama : '-'
            },
            aw:{
                title : '',
                ppk : true
            },
            assignNip : '',
            inputState : null

        }
    },
    computed:{
        nipState(){
            axios.post('admin/action/verify-nip',{assignedNip : this.assignNip})
                .then(r => {
                    if(this.assignNip.length<1)
                        this.makeNull();
                    else{

                        if(r.data == 1)
                            this.makeTrue();
                        else
                            this.makeFalse();
                    }
                })
                .catch(e =>{
                    this.makeFalse();
                });
        },

        nip(value){
            if(/^(\d|[+-])+$/.test(value))
            return value;
        }
    },
    methods:{
        
        // start complain about stupid and asynchronous axios
        makeTrue(){
            this.inputState = true;
        },

        makeFalse(){
            this.inputState = false;
        },
        makeNull(){
            this.inputState = null;
        },
        // end complain about stupid and asynchronous axios

        showWindow(){
            this.refresh();
            this.$refs['ppkWindow'].show();
        },        
        hideWindow(){
            this.$refs['ppkWindow'].hide();
        },

        changePPK(){
            this.aw.ppk = true;
            this.aw.title = 'PPK';
            this.$refs['assignWindow'].show();
        },

        changePPTK(){
            this.aw.ppk = false;
            this.aw.title = 'PPTK';
            this.$refs['assignWindow'].show();
        },

        batal(){

            this.selected.nip = '-';
            this.selected.nama = '-';
            this.selected.golongan = '-';
            this.selected.jabatan = '-';
            this.selected.penempatan = '-';

            this.nipState = null;
            this.assignNip = '';
            this.$refs['assignWindow'].hide();
        },

        submit(){
            this.form.nip = this.assignNip;
            this.form.ppk = this.aw.ppk;

            axios.post('admin/action/assign-ppk-pptk',this.form)
            .then(r => {
                var m;

                switch(r.data){
                    case 'success_add_ppk-pptk':m="Berhasil mengganti data "+this.aw.title+"!";
                                                this.refresh();
                                                this.batal();
                                                break;
                    case 'fail_add_ppk-pptk_try_caught':m='Gagal mengganti data '+this.aw.title+'! (Error Query/DB)';break;
                    case 'fail_add_ppk-pptk_exist':m=''+this.aw.title+' dengan nip tersebut sudah ada!';break;
                    case 'fail_add_ppk-pptk_both_null':m='Pemegang jabatan '+this.aw.title+' sudah tidak ada!';break;
                    default:m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(e =>{
                alert('terjadi error : '+e);
            });
        },

        refresh(){

            this.selected.nip = '-';
            this.selected.nama = '-';
            this.selected.golongan = '-';
            this.selected.jabatan = '-';
            this.selected.penempatan = '-';

            axios.get('/admin/list-ppk')
            .then(resp => {
                let d = resp.data;
                this.ppk.nip = d[0];
                this.ppk.nama = d[1];
                this.pptk.nip = d[2];
                this.pptk.nama = d[3];
            })
            .catch(err => {
                console.log('error fetch list jabatan on PLT Add : '+err);
            });
        },

        selectNip(){

            axios.post('admin/action/verify-nip',{assignedNip : this.assignNip})
            .then(r => {
                console.log('r.data : '+r.data);
                
                if(r.data == 1)
                    this.prepareData();
                else
                    alert('NIP '+this.assignNip+' tidak ditemukan!');
            })
            .catch(e =>{
                alert('terjadi error : '+e);
            });
        },

        prepareData(){
            axios.post('admin/action/f',{assignedNip : this.assignNip})
            .then(r => {

                this.selected.nip = this.assignNip;
                this.selected.nama = r.data[0].nama;
                this.selected.golongan = r.data[0].golongan;
                this.selected.jabatan = r.data[0].jabatan;
                this.selected.penempatan = r.data[0].penempatan;

            })
            .catch(e =>{
                alert('terjadi error : '+e);
            });
        },


    }
}

</script>
