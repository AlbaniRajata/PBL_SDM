@extends('layouts.template')

@section('content')
    <!-- Include Google Fonts and Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .card-title {
            font-size: 1.25rem;
        }
        .card-text {
            font-size: 2.5rem;
        }
        .icon {
            font-size: 3rem;
            margin-right: 10px;
        }
    </style>
    <!-- Notifikasi Kegiatan Akan Datang -->
    @if ($kegiatanAkanDatang->count() > 0)
    <div class="alert alert-info">
        <h5><i class="icon fas fa-info"></i> Kegiatan Akan Datang</h5>
        <ul>
            @foreach ($kegiatanAkanDatang as $kegiatan)
                <li>
                    {{ $kegiatan->nama_kegiatan }} - {{ $kegiatan->tanggal_mulai}}
                </li>
            @endforeach
        </ul>
    </div>
@endif

       <!-- Kalender Kegiatan -->
        <div class="card bg-light shadow-sm mt-4">
            <div class="card-header">
                <h3 class="card-title">Kalender Kegiatan</h3>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
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
                events: '/api/kegiatan/events',  // Ensure this API returns event data
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },
                locale: 'id'  // Set locale to Indonesian
            });
            calendar.render();
        });
    </script>
@endsection
