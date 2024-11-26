@extends('layouts.template')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <h5>Politeknik Negeri Malang</h5>
                        <p class="text-muted">Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15806.010732341647!2d112.6161209!3d-7.9468912!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827687d272e7%3A0x789ce9a636cd3aa2!2sPoliteknik%20Negeri%20Malang!5e0!3m2!1sid!2sid!4v1714835289599!5m2!1sid!2sid" style="border:;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
                <div class="contact-info mt-4">
                    <h4>Hubungi Kami</h4>
                    <ul>
                        <hr>
                        <a href="tel:0341404424">
                            <li>
                                <span class="icon"><i class="fas fa-phone"></i></span>
                                (0341) 404424
                                <i class="bi bi-arrow-right" style="margin-left: auto"></i>
                            </li>
                        </a>
                        <hr>
                        <a href="mailto:humas@polinema.ac.id">
                            <li>
                                <span class="icon"><i class="fas fa-envelope"></i></span>
                                humas@polinema.ac.id
                                <i class="bi bi-arrow-right" style="margin-left: auto"></i>
                            </li>
                        </a>
                        <hr>
                        <li>
                            <span class="icon"><i class="fas fa-clock"></i></span>
                            <a>Jam Kerja :</a>
                            <li style="margin-left: 33px;">Senin - Jumat : 07:00 - 16:00 WIB</li>
                            <li style="margin-left: 33px;">Sabtu & Minggu : Tutup</li>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .content-wrapper {
        padding-bottom: 5px;
    }

    .map-container {
        width: 100%;
        height: 500px;
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
        border-radius: 8px;
    }

    .contact-info {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-top: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .contact-info h4 {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .contact-info ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .contact-info li {
        display: flex;
        align-items: center;
        padding: 10px 0;
        font-size: 16px;
        color: #555;
        border-bottom: 1px solid #ddd;
    }

    .contact-info li:last-child {
        border-bottom: none;
    }

    .contact-info .icon {
        margin-right: 15px;
        color: #555;
    }

    .contact-info a {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
    }

    .contact-info a:hover {
        color: #007bff;
    }

    @media (max-width: 768px) {
        .map-container {
            height: 300px;
        }

        .contact-info h4 {
            font-size: 18px;
        }

        .contact-info li {
            font-size: 14px;
        }
    }
</style>


<script src="{{url ('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{url ('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>