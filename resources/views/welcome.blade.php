@extends('layouts.template')

@section('content')
<div class="card bg-light shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Kalender Kegiatan</h3>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Include FullCalendar CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Render FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: '/api/kegiatan/events',  // Pastikan API ini mengembalikan data acara
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            alert('Event: ' + info.event.title + '\nDeskripsi: ' + info.event.extendedProps.description);
        }
    });
    calendar.render();
});
</script>

<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        height: 10vh; /* Membuat kalender memenuhi tinggi layar */
    }
    .card-body {
        padding: 0; /* Menghilangkan padding pada card body untuk kalender penuh */
    }
</style>

@endsection
