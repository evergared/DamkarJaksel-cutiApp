<template>
    <div class="col-lg-8 col-md-7">
      <div class="card bg-secondary shadow border-0 xl-12">
          <div class="card-body">
              <h2 class="card-title">Form Event</h2>
              <form @submit.prevent>

                  <div class="form-group">
                      <label for="event_name">Nama Event</label>
                      <input type="text" id="event_name" class="form-control" v-model="newEvent.event_name">
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

                  <tempate v-else> 
                      <div class="col-md-6 mb-4">
                      <button class="btn btn-sm btn-success" @click="updateEvent">Perbarui Event</button>
                      <button class="btn btn-sm btn-danger" @click="deleteEvent">Hapus Event</button>
                      <button class="btn btn-sm btn-secondary" @click="addingMode = !addingMode">Batal</button>
                      </div>
                  </tempate>

                  </div>
              </form>
          </div>
      </div>

    <div class="col-md-8">
        <Fullcalendar @eventClick="showEvent" :plugins="calendarPlugins" :events="events" />
    </div>


    </div>
</template>

<script>

import "@fullcalendar/core/vdom"
import Fullcalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import listPlugin from "@fullcalendar/list";
import axios from "axios";

    export default {
        components:{
            Fullcalendar
        },
        data(){
            return {
                calendarPlugins: [dayGridPlugin,interactionPlugin,listPlugin],
                events:"",
                newEvent:{
                    event_name:"",
                    event_start:"",
                    event_end:""
                },
                addingMode : true,
                indexToUpdate : ""
            };
        },

        created(){
            this.getEvents();
        },

        methods: {
            addNewEvent(){
                axios.post("/calendar/create",{...this.newEvent})
                .then(data=>{
                    this.getEvents();
                    this.resetForm();
                })
                .catch(err => console.log("Failed to create new event for FullCalendar", err.response.data));
            },

            showEvent(arg){
                this.addingMode = false;
                const {id, title, start, end} = this.events.find(event => event.id === +arg.event.id);
                this.indexToUpdate = id;
                this.newEvent = {
                    event_name : title,
                    event_start : start,
                    event_end : end
                };
            },

            updateEvent(){
                axios.put("/calendar/update" + this.indexToUpdate, {
                    ...this.newEvent
                })
                .then(resp => {
                    this.resetForm();
                    this.getEvents();
                    this.addingMode = !this.addingMode;
                })
                .catch(err =>
                console.log("Failed to update event for FullCalendar", err.response.data));
            },

            deleteEvent() {
                axios.delete("/calendar/delete")
                .then(resp => {
                    this.resetForm();
                    this.getEvents();
                    this.addingMode = !this.addingMode;
                    
                })
                .catch( err => console.log("Failed to delete event for FullCalendar", err.response.data));
            },

            getEvents(){
                axios
                .get("/calendar/index")
                .then(resp => (this.events = resp.data.data))
                .catch(err => console.log(err.response.data));
            },

            resetForm(){
                Object.keys(this.newEvent).forEach(key => {
                    return (this.newEvent[key] = "");
                });
            }

        },

        watch: {
            indexToUpdate(){
                return this.indexToUpdate;
            }
        }
        
    };
</script>