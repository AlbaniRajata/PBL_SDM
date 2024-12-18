@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Statistik Dosen</h3>
        <div class="card-tools">
            <a href="{{ url('/admin/statistik/export_excel') }}" class="btn btn-sm btn-outline-success btn-hover">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/admin/statistik/export_pdf') }}" class="btn btn-sm btn-outline-warning btn-hover">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="row mb-3">
    <div class="col-md-12 ">
        <label class="control-label col-form-label me-3">Filter:</label>
        <select class="form-control me-5" id="min-max-point" name="point">
            <option value="">- Filter Poin -</option>
            <option value="0-10">0 - 10</option>
            <option value="11-30">11 - 30</option>
            <option value="31-50">31 - 50</option>
            <option value=">51">>51</option>
        </select>
        <label for="periode" class="me-3">Periode Kegiatan:</label>
        <select class="form-control" id="periode" name="periode">
            <option value="">Pilih Periode Kegiatan</option>
            @foreach($years as $year)
                <option value="{{ $year }}">Periode {{ $year }} / {{ $year + 1 }}</option>
            @endforeach
        </select>
    </div>
</div>



        <table class="table table-bordered table-striped table-hover table-sm" id="statistik-dosen-table">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Dosen</th>
                    <th class="text-center">Total Kegiatan</th>
                    <th class="text-center">Total Poin</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kegiatan Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailModalContent">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Ensure CSRF token is set for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable with comprehensive error handling
        var statistikTable = $('#statistik-dosen-table').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.statistik.list') }}",
                type: "POST",
                data: function(d) {
                    d.point_filter = $('#min-max-point').val();
                    d.periode = $('#periode').val(); // Tambahkan '#' sebelum 'periode'
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables Ajax error:', {
                        xhr: xhr, 
                        error: error, 
                        thrown: thrown
                    });
                    
                    // More detailed error handling
                    var errorMsg = 'Unknown error';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMsg = xhr.responseText;
                    }
                    
                    alert('Error loading data: ' + errorMsg);
                    
                    // Optional: Reload the page or reset the table
                    statistikTable.ajax.reload();
                }
            },
            columns: [
    { 
        data: 'DT_RowIndex', 
        name: 'DT_RowIndex', 
        orderable: false, 
        searchable: false,
        className: "text-center" // Tambahkan ini
    },
    { 
        data: 'nama', 
        name: 'nama', 
        searchable: true,
        className: "text-center" // Tambahkan ini
    },
    { 
        data: 'total_kegiatan', 
        name: 'total_kegiatan', 
        searchable: false,
        className: "text-center" // Tambahkan ini
    },
    { 
        data: 'total_poin', 
        name: 'total_poin', 
        searchable: false,
        className: "text-center" // Tambahkan ini
    },
    { 
        data: 'aksi', 
        name: 'aksi', 
        orderable: false, 
        searchable: false,
        className: "text-center" // Tambahkan ini
    }
],


            language: {
                processing: "Loading data...",
                zeroRecords: "No matching records found",
                emptyTable: "No data available in table"
            }
        });

        // Filtering functionality
        $('#min-max-point').on('change', function() {
            statistikTable.draw();
        });
    });

    // Detail modal function
    function showDetails(dosenId) {
    // Show loading indicator
    $('#detailModalContent').html(`
        <div class="text-center p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail...</p>
        </div>
    `);
    
    $.ajax({
        url: "{{ route('admin.statistik.details') }}",
        method: 'POST',
        data: { dosen_id: dosenId },
        dataType: 'json',
        timeout: 10000, // 10 second timeout
        success: function(response) {
            if (response.html) {
                $('#detailModalContent').html(response.html);
                $('#detailModal').modal('show');
            } else if (response.error) {
                handleError(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching details:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });

            let errorMessage = 'Gagal memuat detail kegiatan';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage += ': ' + xhr.responseJSON.message;
            } else if (status === 'timeout') {
                errorMessage = 'Waktu permintaan habis. Silakan coba lagi.';
            }

            handleError(errorMessage);
        }
    });
}

function handleError(message) {
    $('#detailModalContent').html(`
        <div class="alert alert-danger text-center" role="alert">
            <i class="fas fa-exclamation-triangle mb-2"></i>
            <p>${message}</p>
            <button onclick="$('#detailModal').modal('hide')" class="btn btn-secondary mt-2">Tutup</button>
        </div>
    `);
    $('#detailModal').modal('show');
}
</script>
@endpush