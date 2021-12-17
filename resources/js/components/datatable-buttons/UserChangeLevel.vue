<template>

<div>
    <button class="btn btn-sm btn-secondary" @click="callWindow">
        <slot>Ubah Level</slot>
    </button>

        <b-modal size='sm' ref="level-window" hide-header hide-backdrop>
            <h3>Ubah Level Akun User</h3>
            
            <b-form-group
            id="input-group-level"
            label="Level Akun : "
            label-for="input-level"
            >
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

            <b-form-group 
            id="input-group-penempatan"
            label="Penempatan : "
            label-for="input-penempatan"
            description="Khusus untuk Ka. Sektor, perlu disertai lokasi penempatan."
            v-if="this.isKasie">
                <b-form-select
                v-model="form.penempatan"
                :options="opsiPenempatan"
                ></b-form-select>
            </b-form-group>

            <template #modal-footer="{}">
                <button type="button" class="btn btn-primary" @click="changeLevel()">Update</button>
                <button type="button" class="btn btn-secondary" @click="hideLevelWindow()">Batal</button>
            </template>
        </b-modal>

</div>
    
</template>
<script>
import axios from 'axios';
import BootstrapVue from 'bootstrap-vue';
import eventbus from '../../eventbus';

Vue.use(BootstrapVue);

export default {
    props:['nip','penempatan','rolesArray'],
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
                nip:this.nip,
                peran:[],
                penempatan:this.penempatan
            },
            isKasie:false,
            isSementara:false,

            tempPeran:[],
            opsiPenempatan:[],
            isASN:false,
            adminDisabled:false,
            tipe:[
                {text: 'PJLP', value: false},
                {text: 'ASN', value: true}
            ],
            levelASN:[
                //{key: 0, text: 'Ka. Sektor', value:"KASIE"},
                {key: 1, text: 'Ka. Pencegahan', value:"KASIE.PENCEGAHAN"},
                {key: 2, text: 'PPK/PPTK',value:'PPK'},
                //{key: 3, text: 'Ka. Sub Bag.TU', value:'KASUBAGTU'},
                //{key: 4, text: 'Ka. Sudin', value:'KASUDIN'}
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
    watch:{
        isASN:function(newVal,oldVal){
            if(this.isASN){
                this.tempPeran = [];
                this.adminDisabled = false;
                this.isKasie = false;
                this.setAllInArray(this.levelDisabledASN,false);

            }
            else{
                this.tempPeran=[];
                this.adminDisabled = false;
                this.isKasie = false;
                this.setAllInArray(this.levelDisabledASN,true);
            }
        },
        tempPeran:function(newVal,oldVal){
            //console.log('temp peran is changed');
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
                        this.isKasie = true;

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

                        this.isKasie = false;
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
        changeLevel(){

            if(this.isASN)
                this.tempPeran.push("ASN");
            else
                this.tempPeran.push("PJLP");

            if(this.isSementara)
                this.tempPeran.push('SEMENTARA');

            this.form.peran = this.tempPeran;

            axios.patch('/admin/action/change-level',this.form)
            .then(resp=>{
                var m;

                switch(resp.data){
                    case 'success_change_level':    m="Berhasil mengubah level akun user!";
                                                eventbus.$emit('draw',{message:'Memuat ulang...'});
                                                this.hideLevelWindow();
                                                break;
                    case 'fail_change_level_not_exist':m='Gagal mengubah level akun user! (Error fetch user)';break;
                    case 'fail_change_level_try_caught':m='Gagal mengubah level akun user! (Error Query/DB)';break;
                    default:m='Unknown Error';break;
                }

                alert(m);
            })
            .catch(err=>{
                alert('Gagal mengubah level akun user! '+err);
            });
        },
        callWindow(){
            if(this.rolesArray.includes('SEMENTARA'))
            {
                this.isSementara = true;
                var a = this.rolesArray.indexOf('SEMENTARA');
                this.rolesArray.splice(a,1);
                //console.log('sementara hit'+a);
            }
            
            if(this.rolesArray.includes('ASN'))
                this.isASN = true;


            this.tempPeran = this.rolesArray;
            //console.log(this.tempPeran);
            this.tempPeran = this.rolesArray;
            this.$refs['level-window'].show();
        },
        hideLevelWindow(){
            // this.form.penempatan = 0
            // this.form.peran = []
            // this.tempPeran = []
            // this.isASN = false
            // this.isKasie = false;
            // this.setAllInArray(this.levelDisabledASN,true);

            this.$refs['level-window'].hide();
        },
        setAllInArray(array,value){
            var i, n = array.length;
            for(i=0;i<n;i++)
                array[i] = value;
        }
    }

}
</script>
