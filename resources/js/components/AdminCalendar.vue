<template>
    <!-- <div class="col-lg col-lg-16"> -->

      <!-- <div class="card bg-secondary shadow border-0 xl-12 mb-3">
          <div class="card-body">
              <h2 class="card-title">Form Event</h2>
              <form @submit.prevent>

                  <div class="form-group">
                      <label for="event_name">Nama Event</label>
                      <input type="text" id="event_name" class="form-control" placeholder="Nama Event" v-model="newEvent.event_name">
                  </div>

                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label for="event_start">Tanggal Awal</label>
                            <input type="date" id="event_start" class="form-control" v-model="newEvent.event_start">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                            <label for="event_end">Tanggal Akhir</label>
                            <input type="date" id="event_end" class="form-control" v-model="newEvent.event_end">
                          </div>
                      </div>

                  <div class="col-md-6 mb-4" v-if="addingMode">
                      <button class="btn btn-sm btn-primary" @click="addNewEvent">Buat Event</button>
                  </div>

                  <template v-else> 
                      <div class="col-md-6 mb-4">
                      <button class="btn btn-sm btn-success" @click="updateEvent">Perbarui Event</button>
                      <button class="btn btn-sm btn-danger" @click="deleteEvent">Hapus Event</button>
                      <button class="btn btn-sm btn-secondary" @click="addingMode = !addingMode">Batal</button>
                      </div>
                  </template>

                  </div>
              </form>
          </div>
      </div> -->
      <div class="row">

                <div class="col-10">
                    <div class="card shadow">
                            <div class="card-body">
                                <Fullcalendar :options="calendarOptions" ref='fc'/>
                            </div>
                    </div>
                 </div>

                 <div class="col-6 mt-3 md-3" >
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body">
                            <h2 class="card-title">Detil Event</h2>
                            <form ref="ev" @submit.prevent>

                                <div class="form-group">
                                    <label for="event_name">Nama Event</label>
                                    <input type="text" id="event_name" class="form-control" placeholder="Nama Event" v-model="newEvent.event_name">
                                </div>

                                <div class="form-group">
                                    <label for="event_calendar">Dari Kalender</label>
                                    <input type="text" id="event_calendar" class="form-control" placeholder="Kalender Asal" v-model="newEvent.event_calendar" disabled>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_start">Tanggal Awal</label>
                                            <input type="date" id="event_start" class="form-control" v-model="newEvent.event_start">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="event_end">Tanggal Akhir</label>
                                            <input type="date" id="event_end" class="form-control" v-model="newEvent.event_end">
                                        </div>
                                    </div>
                                </div>

                                <div class="row-md-6 mb-4" >
                                    <button class="btn btn-sm btn-primary">Update</button>
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                 </div>

</div>



</template>

<script>

import "@fullcalendar/core/vdom"
import Fullcalendar, { isDateSelectionValid } from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import idLocale from "@fullcalendar/core/locales/id";
import axios from "axios";

    export default {
        components:{
            Fullcalendar
        },
        data(){
            return {
                    calendarOptions:{
                        plugins: [dayGridPlugin,interactionPlugin],
                        initialView:'dayGridMonth',
                        locale:idLocale,
                        eventSources : ['/calendar/json'],
                        headerToolbar :{
                            start : "",
                            center : "title",
                            end : "today prev,next"
                        }
                },
                newEvent:{
                            event_calendar:"",
                            event_calendarId:"",
                            event_id:"",
                            event_name:"",
                            event_start:"",
                            event_end:""
                        },
                eventClick:function(ev){
                    this.event_name = ev.event.title;
                }
            }
        },
    };
</script>