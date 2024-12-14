@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="row" id="user-list">
            <!-- Cards will be populated dynamically -->
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* Global styling */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fa;
        color: #333;
    }

    /* Styling for the card container */
    #user-list {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: center;
    }

    /* Styling for each card */
    .card-level {
        width: 250px;
        border-radius: 12px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 16px;
        text-align: center;
    }

    /* Hover effect for the cards */
    .card-level:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Styling for the level name */
    .level-text {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
    }

    /* Icon styling */
    .level-icon {
        font-size: 24px;
        color: #fff;
        margin-bottom: 12px;
    }

    /* Custom colors for each level */
    .admin {
        background: linear-gradient(135deg, #ff4b5c, #ff6a00); /* Admin card gradient */
    }

    .pimpinan {
        background: linear-gradient(135deg, #00bcd4, #00897b); /* Pimpinan card gradient */
    }

    .dosen {
        background: linear-gradient(135deg, #8e24aa, #d500f9); /* Dosen card gradient */
    }

    /* Special background gradient for the card */
    .card-body {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 600px) {
        .card-level {
            width: 100%;
        }
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        var staticData = [
            { level: 'admin', class: 'admin' },
            { level: 'pimpinan', class: 'pimpinan' },
            { level: 'dosen', class: 'dosen' }
        ];

        // Loop through data and append it as cards
        var userList = $('#user-list');
        staticData.forEach(function(item) {
            var card = $('<div>', {
                class: 'col-md-4 ' + item.class, // Adding unique class for each level
                html: `
                    <div class="card-level ${item.class}">
                        <div class="level-icon">🔑</div>
                        <div class="level-text">${item.level}</div>
                    </div>
                `
            });
            userList.append(card);
        });
    });
</script>
@endpush
