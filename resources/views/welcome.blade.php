@extends('layouts.template')

@section('content')
    <!-- Include Google Fonts and Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.welcome.css') }}">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="card bg-info text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-user-tie icon"></i>
                        <div>
                            <h5 class="card-title">Jumlah Dosen</h5>
                            <p class="card-text">{{ $jumlahDosen }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-chalkboard-teacher icon"></i>
                        <div>
                            <h5 class="card-title">Jumlah Kegiatan JTI</h5>
                            <p class="card-text">{{ $jumlahKegiatanJTI }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-tasks icon"></i>
                        <div>
                            <h5 class="card-title">Jumlah Kegiatan Non-JTI</h5>
                            <p class="card-text">{{ $jumlahKegiatanNonJTI }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kalender Kegiatan -->
        <div class="card bg-light shadow-sm mt-4">
            <div class="card-header">
                <h3 class="card-title">Kalender Kegiatan Jurusan Teknologi Informasi Tahun 2024</h3>
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
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            locale: 'id', // Set locale to Indonesian
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
            },
            events: '/api/kegiatan/events', // API untuk data event
            eventColor: '#FFAE03', // Warna default event
            eventBorderColor: '#FFA303', // Border event
            eventTextColor: '#ffffff', // Warna teks event
            themeSystem: 'bootstrap' // Jika Anda menggunakan Bootstrap
        });
        calendar.render();
    });
</script>
@endsection
