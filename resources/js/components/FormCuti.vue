<template>
    <div class="">
      <div class="col card shadow border-0 xl-12">

        <div class="card-body">
          <div class="text-center">
            <h1 v-if="!um">Form Pengajuan Cuti</h1>
            <h1 v-else>Ubah Data Cuti</h1>
          </div>
          <div class="col">


                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="alert.type === 'failed'">
                    <span class="alert-inner--icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="alert-inner--text">{{ alert.message }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>

                
                    <div class="alert alert-success alert-dismissible fade show" role="alert" v-else-if="alert.type === 'success'">
                    <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-inner--text">{{ alert.message }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>

          
          

            <form @submit.prevent>
              <div class="form-group">

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-address-card"></i></span>
                  </div>
                  <input class="form-control" id="nrk" name="nrk" placeholder="NRK/NIP" type="text" v-model="form.nip" readonly = "true">
                </div>

                <div class="row justify-content-center align-items-center my-3">
                  <div class="input-group col">
                    <b-form-datepicker v-model="form.start" @input="calculateHari()" :date-disabled-fn="dateDisabled" :min="minDate" :max="maxDate" :start-weekday="1" placeholder="Tanggal Mulai" locale="id"></b-form-datepicker>
                  </div>
                  <span class="col-sm-auto my-1 mx-auto"><small>Sampai Dengan</small></span>
                  <div class="input-group col">
                    <b-form-datepicker v-model="form.end" @input="calculateHari()" :date-disabled-fn="dateDisabled" :min="form.start" :max="maxDate" :start-weekday="1" placeholder="Tanggal Akhir" locale="id"></b-form-datepicker>
                  </div>
                
                <div class="w-100"></div>
                  
                  <div style="display: block;" class="col mt-2" v-if="form.lama > 0">
                      <small>Perkiraan Durasi Cuti : {{form.lama}} Hari (Maks : {{form.batashari}}) </small>
                  </div>
                  

                </div>
                
                <!-- {{-- Bagian Dropdown Jenis Cuti --}} -->

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-clipboard"></i></span>
                  </div>
                  <select autocomplete="off" class="form-control" id="jenisCuti" name="jenisCuti" type="text" placeholder="Pilih Jenis Cuti" v-model="form.jenisCuti">
                      
                        <option value="" disabled>Pilih Jenis Cuti</option >
                        <option value = "Tahunan">Tahunan</option >
                      
                  </select>
                </div>

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-route"></i></span>
                  </div>
                  <input class="form-control" id="alamat" name="alamat" placeholder="Alamat Lengkap Selama Menjalankan Cuti" type="text" v-model="form.alamat" >
                </div>

                <!-- {{-- Bagian Alasan Cuti --}} -->
                <div class="input-group">
                  <!-- {{-- utk attrib textarea Class="form-control" biasa, menyebabkan bug saat di resize --}} -->
                  <textarea class="form-control" id="aCuti" name="aCuti" style="resize:none;" rows="5" placeholder="Alasan Cuti" v-model="form.alasan"></textarea>
                </div>

                <!-- {{-- Bagian tombol submit --}} -->
                <div class="text-center">
                  <a class="btn btn-primary my-4 text-white" id="btn-submit-cuti"  @click="checkDataSubmit()" v-if="!um">Submit</a>
                  <a class="btn btn-primary my-4 text-white"  @click="formUpdate()" v-else>Update</a>
                </div>


                <b-modal  ref="modal1" id="modal1" cancel-disabled ok-disabled  >

                            Perhitungan jumlah cuti yang diambil : {{form.jumlahHari}} hari (maks : {{form.batashari}})<br>
                            <span v-if="form.jumlahHari > form.batashari"><small>Hanya {{form.batashari}} hari yang diterima dari {{form.jumlahHari}} hari yang diajukan. Terhitung mulai dari tanggal awal ({{form.start}})</small></span><br><br>
                            Berikut ini ajuan tanggal cuti yang diterima : <br><br>
                            <ol>
                                <li v-for="tgl in dataCuti.tanggal" :key="tgl"><small>{{ tgl }}</small></li>
                            </ol>

                    <template #modal-footer="{}">
                        <button type="button" class="btn btn-primary" @click="formSubmit()">Setuju dan Buat</button>
                        <button type="button" class="btn btn-secondary" @click="modal1Cancel()">Batal</button>
                    </template>
                </b-modal>         

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import BootstrapVue from 'bootstrap-vue';
//import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import eventbus from '../eventbus';
import axios from 'axios';

Vue.use(BootstrapVue);

export default{

    mounted(){
            axios.get('/calendar/array').then(resp=>{
                this.disableCuti = resp.data;
            });
            eventbus.$on('cuti-update-confirm-'+this.index,(payload) => {
              this.handleUpdateRequest();
            })
    },

    data(){

        const now = new Date()
        const tmp = new Date(now.getFullYear(), now.getMonth(), now.getDate()+10)
        const minDate = new Date(tmp);
<<<<<<< Updated upstream
        const maxDate = new Date(now.getFullYear(),'11','31');
=======
        const maxDate = new Date(now.getFullYear() + 1,'11','31');
>>>>>>> Stashed changes
        //const maxDateBatas = new Date(new Date(form.start).getFullYear(), new Date(form.start).getMonth(), new Date(form.start).getDate()+this.batashari)
        

        return{
            minDate, maxDate,

            hari:{
                minggu:0,
                senin:1,
                selasa:2,
                rabu:3,
                kamis:4,
                jumat:5,
                sabtu:6
            },

            alert:{
                type:"",
                message:""
            },

            form:{
                nip:this.nip,
                start: this.tgl_awal,
                end:this.tgl_akhir,
                jenisCuti:this.jenis_cuti,
                alamat:this.alamat,
                alasan:this.alasan,
                jumlahHari:0,
                lama: this.total_cuti,
                batashari:this.sisa,
                updateMode : this.um,
                canUpdate : false,
                tanggal : []
            },

            dataCuti:{
                nip: "",
                no_cuti: "",
                start:"",
                end:"",
                lama:0,
                tanggal:[],
                jenisCuti:"",
                alamat:"",
                alasan:"",
            },

            disableCuti : [],

        }
    },
    props:{
        nip:{
            type: String,
        },
        no_cuti:{
          type:String,
          default: null
        },
        tgl_awal:{
            type:String,
        },
        tgl_akhir:{
            type:String,
        },
        total_cuti:{
          type:Number,
          default:0
        },
        jenis_cuti:{
            type: String,
        },
        alamat:{
            type:String,
        },
        alasan:{
            type: String,
        },
        sisa:{
            type: Number,
            default:20
        },
        um:{
            type: Boolean,
            default:false
        },
        index:{
          default:null
        }
    },
    methods:{

        calculateHari(){

            if(this.form.start !=="" && this.form.end !== "")
            {
                this.form.lama = (new Date(this.form.end).getTime() - new Date(this.form.start).getTime())/(1000*60*60*24);
            }
            else
                this.form.lama = 0;

        },

        dateDisabled(ymd, date){

            const weekday = date.getDay();

            return weekday === this.hari.minggu || weekday === this.hari.sabtu || this.disableCuti.includes(ymd);

        },
        checkDataSubmit(){

            if( this.form.lama > 0)
            {
                var baseDate = new Array();
<<<<<<< Updated upstream
                for(var i = 0; i<this.form.lama; i++)
=======
                for(var i = 0; i<=this.form.lama; i++)
>>>>>>> Stashed changes
                {
                    var date = new Date(this.form.start);
                    var ndate = new Date(date.setDate(date.getDate() + i));

                    if((ndate.getDay() != this.hari.minggu && ndate.getDay() != this.hari.sabtu) && !this.disableCuti.includes(ndate.toISOString().slice(0,10)))
                    {
                        //console.log("inserted = "+ndate.toISOString().slice(0,10));
                        //baseDate.push(ndate.toLocaleString());
                        baseDate.push(ndate.toISOString().slice(0,10));
                    }
                    else
                        continue
                    
                }
                this.form.jumlahHari = baseDate.length;
                this.dataCuti.tanggal = baseDate.slice(0,this.form.batashari);
                this.$refs['modal1'].toggle('#btn-submit-cuti');
                
            }
            else
                alert('Harap periksa masukan Tanggal Mulai dan Tanggal Akhir anda');
        },
        formSubmit(){
            
            this.dataCuti.nip = this.form.nip;
            this.dataCuti.start = this.form.start;
            this.dataCuti.end = this.form.end;
            this.dataCuti.jenisCuti = this.form.jenisCuti;
            this.dataCuti.alamat = this.form.alamat;
            this.dataCuti.alasan = this.form.alasan;
            this.dataCuti.lama = this.form.jumlahHari;

            this.$refs['modal1'].hide();

            // some bootstrap spinner while waiting would be nice

            axios.post(`form/create`,this.dataCuti)
            .then(resp => {
                var m;

                  switch(resp.data)
                  {
                    case "fail_role_not_found": m = "Autentikasi gagal! Silahkan logout dan login kembali untuk memulihkan."; break;
                    case "success_submit" : m = "Permohonan cuti berhasil dibuat! Cek Report Daftar Cuti untuk melihat persetujuan.";this.clear();break;
                    case "fail_submit_try_caught" : m = "Gagal membuat permintaan cuti! Coba lagi dalam beberapa saat atau hubungi admin jika tetap gagal."; break;
                    default : m = "Error : Status unknown";break;
                  }

                  alert(m);
            })
            .catch( err =>{
                console.log("Error submit cuti : "+err);
                alert("Gagal terhubung ke database!");
            });
        },
        formUpdate(){
            
            if( this.form.lama > 0)
            {
                var baseDate = new Array();
                for(var i = 0; i<this.form.lama; i++)
                {
                    var date = new Date(this.form.start);
                    var ndate = new Date(date.setDate(date.getDate() + i));

                    if((ndate.getDay() != this.hari.minggu && ndate.getDay() != this.hari.sabtu) && !this.disableCuti.includes(ndate.toISOString().slice(0,10)))
                    {
                        baseDate.push(ndate.toISOString().slice(0,10));
                    }
                    else
                        continue
                    
                }
                this.form.jumlahHari = baseDate.length;
                this.dataCuti.tanggal = baseDate.slice(0,this.form.batashari);

                this.dataCuti.nip = this.form.nip;
                this.dataCuti.no_cuti = this.no_cuti;
                this.dataCuti.start = this.form.start;
                this.dataCuti.end = this.form.end;
                this.dataCuti.jenisCuti = this.form.jenisCuti;
                this.dataCuti.alamat = this.form.alamat;
                this.dataCuti.alasan = this.form.alasan;
                this.dataCuti.lama = this.form.jumlahHari;

                this.form.tanggal = this.dataCuti.tanggal

                eventbus.$emit('cuti-update-callback-'+this.index,this.form);
            }
            else{
                alert('Harap periksa masukan Tanggal Mulai dan Tanggal Akhir anda');
                this.form.canUpdate = true;
            }
            
        },
        handleUpdateRequest(){
                axios.patch(`form/update`,this.dataCuti)
                .then(resp => {

                  var m;

                  switch(resp.data)
                  {
                    case "success_update" : m = "Update data cuti berhasil!";this.clear();eventbus.$emit('cuti-update-success-'+this.index);break;
                    case "fail_update_try_caught" : m = "Data cuti gagal di update!"; break;
                    default : m = "Error : Status unknown";break;
                  }

                  alert(m);
                  
                })
                .catch(err => {
                  console.log("Update cuti gagal : "+err);
                  alert("Gagal terhubung ke database");
                });
        },
        clear(){
          this.form.start = "";
          this.form.end = "";
          this.form.jenisCuti = "";
          this.form.alamat = "";
          this.form.jumlahHari = 0;
          this.form.lama = 0;
          this.form.alasan = "";
        },

        modal1Cancel(){
          this.$refs['modal1'].hide()
        }
    }


}
</script>
