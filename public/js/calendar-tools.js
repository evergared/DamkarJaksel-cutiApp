/**
|---------------------------------------------------------------------------------------|
| File yang difungsikan untuk membantu membuat template kalender Cuti dengan datepicker |
| Tujuan file ini yaitu berinteraksi dengan Service(s) yang bersangkutan                |
| dan memberikan data yang kemudian di gunakan bersamaan dengan datepicker.             |
| Pada file ini juga pengaturan untuk datepicker di inisiasikan.                        |
|                                                                                       |
| Datepicker yang digunakan : bootstrap-datepicker oleh uxsolutions                     |
| Link : https://github.com/uxsolutions/bootstrap-datepicker                            |
|---------------------------------------------------------------------------------------|
*/

/**
 * --------------------------------
 * | Bagian Inisiasi Variabel     |
 * --------------------------------
 */

let disabledCuti = [];
let disabledCutiStatus = true;

let enabledCuti = [];
let enabledCutiStatus = true;

let liburNasional = [];
let liburNasionalStatus = true;


var disabledDates = [];

if(disabledCutiStatus)
{
  disabledDates.push(disabledCuti);

  if(enabledCutiStatus)
  {
    
  }
}


/*  -------------------------
   | Bagian datepicker      |
   -------------------------
*/

  $('.input-daterange').datepicker({
    language:'id',
    format:'yyyy-mm-dd',
    weekStart:1,
    startView:1,
    minViewMode:0,
    maxViewMode:2,
    calendarWeeks:true,
    todayHighlight:true,
    startDate:'+10d',
    autoclose:false,
    todayBtn:'linked',
    clearBtn:true,
    toggleActive:true,
  
    // pengaturan kondisional
    daysOfWeekDisabled:'0,6', // TODO : if else function, jika staff maka sabtu minggu disabled.
    setDatesDisabled:'' // TODO : masukan data array untuk menonaktifkan tanggal-tanggal tertentu.
  });

  $('#admin-datepicker').datepicker({
    language:'id',
    format:'yyyy-mm-dd',
  })




/*
Salah satu Service atau layanan yang digunakan ialah Google Calendar.
Fungsinya adalah untuk mendapatkan data :
- Tanggal-tanggal merah / libur nasional
*/
