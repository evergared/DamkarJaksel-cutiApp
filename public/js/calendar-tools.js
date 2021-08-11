/**
|---------------------------------------------------------------------------------------|
| File yang difungsikan untuk membantu membuat template kalender Cuti dengan datepicker |
| Tujuan file ini yaitu berinteraksi dengan Service(s) yang bersangkutan                |
| dan memberikan data yang kemudian di gunakan bersamaan dengan datepicker.             |
| Pada file ini juga pengaturan untuk datepicker di inisiasikan                         |
|---------------------------------------------------------------------------------------|
*/

/*  -------------------------
   | Bagian datepicker      |
   -------------------------
*/



$('.datepicker .input-daterange .form-control').datepicker({
  language:'id';
  format:'dd/mm/yyyy';
  weekStart:1;
  minViewMode:1;
  maxViewMode:2;
  calendarWeeks:true;
  todayHighlight:true;
  startDate:'+10d';
  autoclose:false;
  clearBtn:true;

  // pengaturan kondisional
  daysOfWeekDisabled:'0,6';
});


/*
Salah satu Service atau layanan yang digunakan ialah Google Calendar.
Fungsinya adalah untuk mendapatkan data :
- Tanggal-tanggal akhir pekan (sabtu - minggu)
- Tanggal-tanggal merah / libur nasional
*/
