<template>
    <div>
        <b-form @submit="submitUserForm" @reset="resetForm">

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
                v-model="form.peran"
                @input="levelOnClick">
                    <b-form-checkbox value="ADMIN" :disabled="adminDisabled">Admin</b-form-checkbox>
                    <b-form-checkbox v-for="peran in level" :key="peran.key" :value="peran.value" :disabled="levelCheck[peran.key]">
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
            opsiPenempatan:[],
            isASN:false,
            adminDisabled:false,
            tipe:[
                {text: 'PJLP', value: false},
                {text: 'ASN', value: true}
            ],
            level:[
                {key: 0, text: 'Ka. Sektor', value:"KASIE"},
                {key: 1, text: 'Ka. Pencegahan', value:"KASIE.PENCEGAHAN"},
                {key: 2, text: 'PPK/PPTK',value:'PPK'},
                {key: 3, text: 'Ka. Sub Bag.TU', value:'KASUBAGTU'},
                {key: 4, text: 'Ka. Sudin', value:'KASUDIN'}
            ],
            levelCheck:[
                false,
                false,
                false,
                false,
                false,
            ]
        }
    },
    computed:{
        isASN: function(val){
            if(val)
                this.setAllInArray(this.form.peran,false);
            else
                this.setAllInArray(this.form.peran, true);
        }
    },
    watch:{
        
    },
    methods:{
        levelOnClick(){
            if(this.isASN)
            {
                if(this.form.peran.includes("ADMIN"))
                {
                    var index = this.form.peran.indexOf("ADMIN");
                    
                    if(index != 0)
                        this.purgeArray(index,this.form.peran);
                    else
                        this.form.peran.length = 1;

                    this.levelCheck[0] = true;
                }
                else if(this.form.peran.includes("KASIE"))
                    {

                        var indexA = this.form.peran.indexOf("KASIE");
                        var indexB = this.form.peran.indexOf("KASIE.PENCEGAHAN");
                        console.log(indexA+'&'+indexB);
                        var temporary = [];

                        if(indexA != -1)
                            temporary.push(this.form.peran[indexA]);
                        if(indexB != -1)
                            temporary.push(this.form.peran[indexB]);
                        
                        if(temporary.length>0)
                        {
                            this.form.peran.length = 0;
                            this.setAllInArray(this.levelCheck,true);
                            this.form.peran = temporary;
                        }

                        if(indexA != -1)
                            this.levelCheck[0]
                        if(indexB != -1)
                            this.levelCheck[1]


                        this.adminDisabled = true;

                        
                    }
                    else if(this.form.peran.includes("KASIE.PENCEGAHAN"))
                    {

                        var indexA = this.form.peran.indexOf("KASIE");
                        var indexB = this.form.peran.indexOf("KASIE.PENCEGAHAN");
                        console.log(indexA+'&'+indexB);
                        var temporary = [];

                        if(indexA != -1)
                            temporary.push(this.form.peran[indexA]);
                        if(indexB != -1)
                            temporary.push(this.form.peran[indexB]);
                        
                        if(temporary.length>0)
                        {
                            this.form.peran.length = 0;
                            this.setAllInArray(this.levelCheck,true);
                            this.form.peran = temporary;
                        }

                        if(indexA != -1)
                            this.levelCheck[0]
                        if(indexB != -1)
                            this.levelCheck[1]


                        this.adminDisabled = true;

                        
                    }
                else if (this.form.peran.includes("PPK"))
                {
                    var index = this.form.peran.indexOf("PPK");
                    
                    if(index != 0)
                        this.purgeArray(index,this.form.peran);
                    else
                        this.form.peran.length = 1;

                    this.adminDisabled = true;
                    
                    if(peran.key != 2)
                        return true;
                }
                else if (this.form.peran.includes("KASUBAGTU"))
                {
                    var index = this.form.peran.indexOf("KASUBAGTU");
                    
                    if(index != 0)
                        this.purgeArray(index,this.form.peran);
                    else
                        this.form.peran.length = 1;
                    
                    this.adminDisabled = true;

                    if(peran.key != 3)
                        return true;
                }
                else if (this.form.peran.includes("KASUDIN"))
                {
                    var index = this.form.peran.indexOf("KASUDIN");
                    
                    if(index != 0)
                        this.purgeArray(index,this.form.peran);
                    else
                        this.form.peran.length = 1;
                    
                    this.adminDisabled = true;
                    
                    if(peran.key != 4)
                        return true;
                }
                else
                {
                    this.adminDisabled = false;
                    return false;
                }
            }
            // PJLP
            else
                {
                    console.log('hit phl')
                    this.setAllInArray(this.levelCheck,true);
                } 
        },
        submitUserForm(){
            if(this.form.bukanPegawai)
                this.form.peran.push("SEMENTARA");
            if(this.isASN)
                this.form.peran.push("ASN");
            else
                this.form.peran.push("PJLP");

            axios.post('/admin/action/add-user',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_add_user':m="Berhasil menambah user!";break;
                    case 'fail_add_user_try_caught':m='Gagal menambah user! (Error Query/DB)';break;
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
                this.form.nama='',
                this.form.peran=[],
                this.form.penempatan=''

                this.setAllInArray(this.levelCheck,true);
        },
        setAllInArray(array,value){
            var i, n = array.length;
            for(i=0;i<n;i++)
                array[i] = value;
        },
        purgeArray(indexSurvivor,array = []){
            array.splice(0,1,array[indexSurvivor]);
            array.length = 1;
        }
    }
}
</script>
