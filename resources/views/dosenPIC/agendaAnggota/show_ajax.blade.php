@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Kegiatan</h3>
            <div class="card-tools">
                <form action="{{ route('kegiatan.upload_dokumen') }}" method="POST" class="form-inline">
                    <input type="text" name="search" class="form-control" placeholder="Cari kegiatan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ml-2">Cari</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th style="width: 5%" class="text-center">No</th>
                        <th style="width: 30%" class="text-center">Nama Kegiatan</th>
                        <th style="width: 50%" class="text-center">Agenda</th>
                        <th style="width: 15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Upload Kegiatan -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id_agenda_anggota" name="id_agenda_anggota">
                    <div class="form-group">
                        <label for="file">Pilih Dokumen</label>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".pdf,.jpg,.jpeg">
                    </div>
                    <div id="uploadError" class="text-danger"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="uploadSubmit">Upload</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('agenda.listAgendaKegiatan') }}",
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center" },
                { data: 'agenda', name: 'agenda', className: "text-center" },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: "text-center" },
            ]
        });
    });

    function uploadKegiatan(id_agenda_anggota) {
    // Reset form and error message
    $('#uploadForm')[0].reset();
    $('#uploadError').text('');
    
    // Set the kegiatan ID in hidden input
    $('#id_agenda_anggota').val(id_agenda_anggota);
    
    // Show modal
    $('#uploadModal').modal('show');
}


    // Handle upload submission
    $('#uploadSubmit').on('click', function() {
        var formData = new FormData($('#uploadForm')[0]);

        $.ajax({
            url: "{{ route('kegiatan.upload_dokumen') }}", // Ensure you have this route defined
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message
                    });

                    // Reload DataTable
                    $('#dataTable').DataTable().ajax.reload();

                    // Close modal
                    $('#uploadModal').modal('hide');
                } else {
                    // Show error message
                    $('#uploadError').text(response.message);
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 400) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    
                    // Compile error messages
                    $.each(errors, function(field, messages) {
                        errorMessage += messages.join('\n') + '\n';
                    });

                    $('#uploadError').text(errorMessage);
                } else {
                    // Generic error
                    $('#uploadError').text('Terjadi kesalahan saat upload');
                }
            }
        });
    });
</script>
@endpush
@endsection