<template>
      <div class='row justify-content-center'>

                <div class="col-xl">
                    <div class="card shadow">
                            <div class="card-body">
                                <Fullcalendar :options="calendarOptions" ref='fc'/>
                            </div>
                    </div>
                 </div>

                 <div class="col-sm mt-3 md-3" >
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h2 class="card-title" v-if="!tambah">Detil Event</h2>
                                    <h2 class="card-title" v-if="tambah">Tambah Event</h2>
                                </div>
                                <div class="col justify-content-center mr-2">
                                    <span class="row justify-content-end">
                                        <button class="btn btn-sm btn-success align-self-end" v-if="!tambah" @click="changeMode()">Tambah Event</button>
                                        <button class="btn btn-sm btn-info align-self-end" v-if="tambah" @click="changeMode()">Detil Event</button>
                                    </span>
                                </div>
                            </div>
                            <form v-if="!tambah" ref="ev" @submit.prevent>

                                <div class="form-group">
                                    <label for="event_name">Nama Event</label>
                                    <input type="text" id="event_name" class="form-control" placeholder="Nama Event" v-model="eventItem.event_name" />
                                </div>
                                
                                <div class="form-group">
                                    <label for="event_calendar">Dari Kalender</label>
                                    <input type="text" id="event_calendar" class="form-control" placeholder="Kalender Asal" v-model="eventItem.event_calendar" disabled>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_start">Tanggal Awal</label>
                                            <input type="date" id="event_start" class="form-control" v-model="eventItem.event_start" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_end">Tanggal Akhir</label>
                                            <input type="date" id="event_end" class="form-control" v-model="eventItem.event_end">
                                        </div>
                                    </div>
                                </div>

                                <div class="row-md-6 mb-4 justify-content-center" >
                                    <button class="btn btn-sm btn-primary" @click="updateEvent()">Update</button>
                                    <button class="btn btn-sm btn-danger" @click="deleteEvent()">Hapus</button>
                                    <button class="btn btn-sm btn-dark" @click="clear()">Batal</button>
                                </div>
                            </form>

                            <form v-if="tambah" ref="nev" @submit.prevent>

                                <div class="form-group">
                                    <label for="event_name">Nama Event</label>
                                    <input type="text" id="event_name" class="form-control" placeholder="Nama Event" v-model="newEvent.event_name" />
                                </div>
                                
                                <div class="form-group">
                                    <label for="event_calendar">Jenis Kalender</label>
                                    <select id="event_calendar" class="form-control"  v-model="newEvent.event_calendar">
                                        <option disabled value="">Pilih kalender</option>
                                        <option>Tidak Boleh Cuti</option>
                                        <option>Boleh Cuti</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_start">Tanggal Awal</label>
                                            <input type="date" id="event_start" class="form-control" v-model="newEvent.event_start" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_end">Tanggal Akhir</label>
                                            <input type="date" id="event_end" class="form-control" v-model="newEvent.event_end">
                                        </div>
                                    </div>
                                </div>

                                <div class="row-md-6 mb-4 justify-content-center" >
                                    <button class="btn btn-sm btn-primary" @click="createEvent()">Tambah</button>
                                    <button class="btn btn-sm btn-dark" @click="clear()">Batal</button>
                                </div>
                            </form>

                        </div>
                    </div>
                 </div>
</div>






</template>

<script>

import "@fullcalendar/core/vdom"
import Fullcalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import idLocale from "@fullcalendar/core/locales/id";
import axios from "axios";
import moment from 'moment';

    export default {
        components:{
            Fullcalendar
        },
        props:{
            eventSources:{
                default:[]
            }
        },
        data(){

            const eventItem = {
                            event_calendar:"",
                            event_calendarId:"",
                            event_id:"",
                            event_name:"",
                            event_start:"",
                            event_end:""
                        }

            const newEvent = {
                            event_calendar:"",
                            event_name:"",
                            event_start:"",
                            event_end:""
                        }

            return {

                eventItem, newEvent,tambah:false,

                    calendarOptions:{
                        plugins: [dayGridPlugin,interactionPlugin],
                        initialView:'dayGridMonth',
                        locale:idLocale,
                        selectable:true,
                        eventSources : this.eventSources,
                        // customButtons:{
                        //     buatEvent:{
                        //         text:"Tambah",
                        //         click:function(){
                        //             sm = !sm;
                        //         }
                        //     }
                        // },
                        headerToolbar :{
                            start : "",
                            center : "title",
                            end : "today prev,next"
                        },
                        //showNonCurrentDates:false,

                        eventClick:function(ev){
                            eventItem.event_calendar=ev.event.extendedProps.calName;
                            eventItem.event_calendarId=ev.event.extendedProps.calId;
                            eventItem.event_id=ev.event.id;
                            eventItem.event_name=ev.event.title;
                            eventItem.event_start=moment(ev.event.start).format("YYYY-MM-DD");
                            if(ev.event.end == ev.event.start)
                            {
                                eventItem.event_end = moment(ev.event.start).format("YYYY-MM-DD");
                                alert(eventItem.event_end);
                            }
                            else{
                            eventItem.event_end=moment(ev.event.end).subtract(1,'d').format("YYYY-MM-DD");
                            }
                        },
                        eventDrop:function(ev){
                            eventItem.event_calendar=ev.event.extendedProps.calName;
                            eventItem.event_calendarId=ev.event.extendedProps.calId;
                            eventItem.event_id=ev.event.id;
                            eventItem.event_name=ev.event.title;
                            eventItem.event_start=moment(ev.event.start).format("YYYY-MM-DD");
                            if(ev.event.end == ev.event.start)
                            {
                                eventItem.event_end = moment(ev.event.start).format("YYYY-MM-DD");
                                alert(eventItem.event_end);
                            }
                            else{
                            eventItem.event_end=moment(ev.event.end).subtract(1,'d').format("YYYY-MM-DD");
                            }
                        },

                        eventResize:function(ev){
                            eventItem.event_calendar=ev.event.extendedProps.calName;
                            eventItem.event_calendarId=ev.event.extendedProps.calId;
                            eventItem.event_id=ev.event.id;
                            eventItem.event_name=ev.event.title;
                            eventItem.event_start=moment(ev.event.start).format("YYYY-MM-DD");
                            if(ev.event.end == ev.event.start)
                            {
                                eventItem.event_end = moment(ev.event.start).format("YYYY-MM-DD");
                                alert(eventItem.event_end);
                            }
                            else{
                            eventItem.event_end=moment(ev.event.end).subtract(1,'d').format("YYYY-MM-DD");
                            }
                        }

                    }
            }
        },
        
        methods:{

            createEvent(){
                axios.post(`/calendar/create`,this.newEvent)
                .then(resp =>{
                    this.clear();
                    this.$refs.fc.getApi().refetchEvents();
                    alert('Event Berhasil Dibuat! Kalender akan memuat ulang...');
                })
                .catch(err => console.log("Fullcalendar error : "+err));
            },

            updateEvent(){
                axios.post(`/calendar/update`,this.eventItem)
                .then(resp =>{
                    this.clear();
                    this.$refs.fc.getApi().refetchEvents();
                    alert('Event Berhasil Diupdate! Kalender akan memuat ulang...');
                })
                .catch(err => console.log("Fullcalendar error : "+err));
            },

            deleteEvent(){

                axios.delete(`/calendar/delete/${this.eventItem.event_calendarId}/${this.eventItem.event_id}`)
                .then(resp =>{
                    this.$refs.fc.getApi().refetchEvents();
                    alert('Event Berhasil Dihapus! Kalender akan memuat ulang...');
                })
                .catch(err => console.log("Fullcalendar error : "+err));

            },

            clear(){

                            this.eventItem.event_calendar="";
                            this.eventItem.event_calendarId="";
                            this.eventItem.event_id="";
                            this.eventItem.event_name="";
                            this.eventItem.event_start="";
                            this.eventItem.event_end="";

                            this.newEvent.event_calendar="";
                            this.newEvent.event_name="";
                            this.newEvent.event_start="";
                            this.newEvent.event_end="";
                
            },

            changeMode(){

                this.tambah = !this.tambah;
                this.clear();

            },

        }
    };
</script>

<style scoped>
.fc-button{
    font-size: 40%;
    width: 40%;
}
</style>