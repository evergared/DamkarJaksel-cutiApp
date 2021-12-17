<template>
    <div>
        <b-form @submit.prevent="submitUserForm" @reset="resetForm">

            <b-form-group
             id="input-group-nip"
             label="NIP / NRK Pegawai : "
             label-for="input-nip"
             description="Jika non pegawai, isi dengan angka acak. NIP/NRK ini akan digunakan saat login.">
                <b-form-input
                 id="input-nip"
                 v-model="form.nip"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
            id="input-group-password"
            label="Password : "
            label-for="input-password" >
                <b-form-input
                 id="input-password"
                 v-model="form.password"
                 required></b-form-input>
            </b-form-group>

            <b-form-group
            id="input-group-email"
            label="Email : "
            label-for="input-email" >
                <b-form-input
                 id="input-email"
                 v-model="form.email"></b-form-input>
            </b-form-group>

            <b-form-group
             id="input-group-bukan-pegawai"
             label="Status Kepegawaian : "
             description="Non-pegawai untuk test, pelaksana tugas, atau memasukan user diluar data pegawai"
             lebel-for="input-bukan-pegawai">
                <b-form-radio-group
                id="input-bukan-pegawai"
                v-model="form.bukanPegawai"
                :options="statusKepegawaian">
                 </b-form-radio-group>
            </b-form-group>

            <b-form-group
            id="input-group-nama"
            label="Nama : "
            label-for="input-nama" 
            v-if="this.form.bukanPegawai">
                <b-form-input
                 id="input-nama"
                 v-model="form.nama"
                 required></b-form-input>
            </b-form-group>

            <b-form-group 
            id="input-group-penempatan"
            label="Penempatan : "
            label-for="input-penempatan"
            v-if="this.form.bukanPegawai">
                <b-form-select
                v-model="form.penempatan"
                :options="opsiPenempatan"
                ></b-form-select>
            </b-form-group>

            <b-form-group
            id="input-group-level"
            label="Level Akun : "
            label-for="input-level"
            v-if="this.form.bukanPegawai">
                <b-form-radio-group
                v-model="isASN"
                :options="tipe"
                ></b-form-radio-group>
                <b-form-checkbox-group
                id="input-level"
                @click.native="levelOnClick"
                v-model="tempPeran"
                >
                    <b-form-checkbox value="ADMIN" :disabled="adminDisabled" checked=true>Admin</b-form-checkbox>
                    <b-form-checkbox v-for="peran in levelASN" :key="peran.key" :value="peran.value" :disabled="levelDisabledASN[peran.key]" >
                        {{peran.text}}
                    </b-form-checkbox>
                </b-form-checkbox-group>
            </b-form-group>

            <b-button type="submit" variant="primary">Submit</b-button>
            <b-button type="reset" variant="danger">Reset</b-button>

        </b-form>
    </div>
</template>
<script>
import axios from 'axios';
import bootstrap from 'bootstrap-vue'
import eventbus from '../eventbus';

Vue.use(bootstrap);

export default {
    mounted(){
        axios.get('/admin/list-penempatan')
        .then(resp => {
            this.opsiPenempatan = resp.data;
        })
        .catch(err => {
            console.log('error fetch list penempatan '+err);
        });
        this.opsiPenempatan.push({value:null,text:'Pilih Penempatan'});
    },
    data(){
        return{
            form:{
                nip:'',
                password:'',
                email:'',
                bukanPegawai:false,
                nama:'',
                peran:[],
                penempatan:''
            },
            statusKepegawaian:[
                {text: 'Pegawai', value:false},
                {text: 'Non-pegawai', value:true}
            ],
            tempPeran:[],
            opsiPenempatan:[],
            isASN:false,
            adminDisabled:false,
            tipe:[
                {text: 'PJLP', value: false},
                {text: 'ASN', value: true}
            ],
            levelASN:[
                {key: 0, text: 'Ka. Sektor', value:"KASIE"},
                {key: 1, text: 'Ka. Pencegahan', value:"KASIE.PENCEGAHAN"},
                {key: 2, text: 'PPK/PPTK',value:'PPK'},
                {key: 3, text: 'Ka. Sub Bag.TU', value:'KASUBAGTU'},
                {key: 4, text: 'Ka. Sudin', value:'KASUDIN'}
            ],
            levelDisabledASN:[
                true,
                true,
                true,
                true,
                true,
            ],
        }
    },
    computed:{

    },
    watch:{
        isASN:function(newVal,oldVal){
            if(this.isASN){
                this.tempPeran = [];
                this.adminDisabled = false;
                this.setAllInArray(this.levelDisabledASN,false);

            }
            else{
                this.tempPeran=[];
                this.adminDisabled = false;
                this.setAllInArray(this.levelDisabledASN,true);
            }
        }
    },
    methods:{
        levelOnClick(event){
            if(event.target.tagName === 'INPUT'){
                if(event.target.checked){
                    if(event.target.value === 'ADMIN')
                    {
                        
                        this.tempPeran = [];

                        this.tempPeran.push('ADMIN');

                        this.setAllInArray(this.levelDisabledASN,true);

                    }
                    else if(event.target.value === 'KASIE')
                    {
                        var indexA = this.tempPeran.includes('KASIE.PENCEGAHAN');
                        var indexB = this.tempPeran.includes('PPK');
                        var temporary = [];

                        if(indexA)
                            temporary.push('KASIE.PENCEGAHAN');
                        if(indexB)
                            temporary.push('PPK');

                        this.tempPeran = [];

                        this.setAllInArray(this.levelDisabledASN,true);
                        this.adminDisabled = true;

                        temporary.push('KASIE');

                        this.tempPeran = temporary;

                        this.levelDisabledASN[0] = false;
                        this.levelDisabledASN[1] = false;
                        this.levelDisabledASN[2] = false;
                    }
                    else if(event.target.value === 'KASIE.PENCEGAHAN')
                    {
                        var indexA = this.tempPeran.includes('KASIE');
                        var indexB = this.tempPeran.includes('PPK');
                        var temporary = [];

                        if(indexA)
                            temporary.push('KASIE');
                        if(indexB)
                            temporary.push('PPK');

                        this.tempPeran = [];

                        this.setAllInArray(this.levelDisabledASN,true);
                        this.adminDisabled = true;

                        temporary.push('KASIE.PENCEGAHAN');

                        this.tempPeran = temporary;


                        this.levelDisabledASN[1] = false;
                        this.levelDisabledASN[2] = false;
                        this.levelDisabledASN[0] = false;
                    }
                    else if(event.target.value === 'PPK')
                    {
                        var indexA = this.tempPeran.includes('KASIE');
                        var indexB = this.tempPeran.includes('KASIE.PENCEGAHAN');
                        var temporary = [];

                        if(indexA)
                            temporary.push('KASIE');
                        if(indexB)
                            temporary.push('KASIE.PENCEGAHAN');

                        this.tempPeran = [];

                        this.setAllInArray(this.levelDisabledASN,true);
                        this.adminDisabled = true;

                        temporary.push('PPK');

                        this.tempPeran = temporary;

                        this.levelDisabledASN[1] = false;
                        this.levelDisabledASN[2] = false;
                        this.levelDisabledASN[0] = false;
                    }
                    else if(event.target.value === 'KASUBAGTU')
                    {

                        this.tempPeran = [];
                        
                        this.tempPeran.push('KASUBAGTU');

                        this.setAllInArray(this.levelDisabledASN,true);
                        this.levelDisabledASN[3] = false;

                        this.adminDisabled = true;
                    }
                    else if(event.target.value === 'KASUDIN')
                    {
                        
                        this.tempPeran = [];

                        this.tempPeran.push('KASUDIN');

                        this.setAllInArray(this.levelDisabledASN,true);
                        this.levelDisabledASN[4] = false;

                        this.adminDisabled = true;
                    }

                }
                else
                {
                    if(event.target.value === 'ADMIN')
                    {
                        this.setAllInArray(this.levelDisabledASN,false);
                        this.tempPeran.pop('ADMIN');
                    }
                    else if(event.target.value === 'KASIE')
                    {
                        var indexA = this.tempPeran.includes("PPK");
                        var indexB = this.tempPeran.includes("KASIE.PENCEGAHAN");

                        if(indexA && indexB )
                        {
                            this.setAllInArray(this.levelDisabledASN,false);
                            this.adminDisabled = false;
                        }

                        this.tempPeran.pop('KASIE');
                    }
                    else if(event.target.value === 'KASIE.PENCEGAHAN')
                    {
                        var indexA = this.tempPeran.includes("PPK");
                        var indexB = this.tempPeran.includes("KASIE");

                        if(indexA && indexB )
                        {
                            this.setAllInArray(this.levelDisabledASN,false);
                            this.adminDisabled = false;
                        }

                        this.tempPeran.pop('KASIE.PENCEGAHAN');
                    }
                    else if(event.target.value === 'PPK')
                    {
                        var indexA = this.tempPeran.includes("KASIE.PENCEGAHAN");
                        var indexB = this.tempPeran.includes("KASIE");

                        if(indexA && indexB )
                        {
                            this.setAllInArray(this.levelDisabledASN,false);
                            this.adminDisabled = false;
                        }

                        this.tempPeran.pop('PPK');
                    }
                    else if(event.target.value === "KASUBAGTU")
                    {
                        this.setAllInArray(this.levelDisabledASN,false);
                        this.adminDisabled = false;
                        this.tempPeran.pop('KASUBAGTU')
                    }
                    else if(event.target.value === "KASUDIN")
                    {
                        this.setAllInArray(this.levelDisabledASN,false);
                        this.adminDisabled = false;
                        this.tempPeran.pop('KASUDIN');
                    }
                   
                }
            }
        },
        submitUserForm(){

            this.form.peran = this.tempPeran;

            if(this.form.bukanPegawai)
            {
                this.form.peran.push("SEMENTARA");
                if(this.isASN)
                    this.form.peran.push("ASN");
                else
                    this.form.peran.push("PJLP");
            }
                

            axios.post('/admin/action/add-user',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_add_user':    m="Berhasil menambah user!";
                                                eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                this.resetForm();
                                                break;
                    case 'fail_add_user_try_caught':m='Gagal menambah user! (Error Query/DB)';break;
                    case 'fail_add_user_exist':m='User telah terdaftar!';break;
                    default:m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(err=>{
                alert('Gagal menambah user! '+err);
            });
        },
        resetForm(){
                this.form.nip='',
                this.form.password='',
                this.form.email='',
                this.form.bukanPegawai=false,
                this.isASN = false;
                this.form.nama='',
                this.form.peran=[],
                this.form.penempatan=''
                this.tempPeran = [];

                this.setAllInArray(this.levelDisabledASN,true);
        },
        setAllInArray(array,value){
            var i, n = array.length;
            for(i=0;i<n;i++)
                array[i] = value;
        },
        purgeArray(indexSurvivor,array = []){
            var t = array[indexSurvivor];
            array.length = 0;
            array.push(t);
        },
        test(){
            this.form.peran = this.tempPeran;
            alert(JSON.stringify(this.tempPeran));
        }
    }
}
</script>
