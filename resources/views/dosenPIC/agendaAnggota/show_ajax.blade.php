@empty($kegiatan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Data Kegiatan</h5>
                    Berikut adalah detail dari data kegiatan
                </div>
                <table class="table table-sm table-bordered table-stripped">
                    <tr>
                        <th class="text-right col-3">Judul Kegiatan : </th>
                        <td class="col-9">{{ $kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi Kegiatan : </th>
                        <td class="col-9">{{ $kegiatan->deskripsi_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Mulai : </th>
                        <td class="col-9">{{ $kegiatan->tanggal_mulai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Selesai : </th>
                        <td class="col-9">{{ $kegiatan->tanggal_selesai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tempat Acara : </th>
                        <td class="col-9">{{ $kegiatan->tempat_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Acara : </th>
                        <td class="col-9">{{ $kegiatan->tanggal_acara }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Kegiatan : </th>
                        <td class="col-9">{{ $kegiatan->jenis_kegiatan }}</td>
                    </tr>
                    </table>
                    </table>

                    <div class="alert alert-info mt-3">
                        <h5><i class="icon fas fa-file"></i> Agenda Anggota</h5>
                        Agenda terkait kegiatan ini
                    </div>
                    <table class="table table-sm table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">Nama Agenda</th>
                                <th class="text-center">Nama Dokumen</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($agendaAnggota as $agenda)
                                <tr>
                                    <td class="text-center">{{ $agenda->nama_agenda }}</td>
                                    <td class="text-center">{{ $agenda->nama_dokumen ?? 'Tidak ada dokumen' }}</td>
                                    <td class="text-center">
                                        @if($agenda->file_path)
                                            <a href="{{ route('kegiatan.download-dokumenagenda', $agenda->id_dokumen) }}" 
                                            class="btn btn-sm btn-primary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada dokumen</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada agenda</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
@endempty

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