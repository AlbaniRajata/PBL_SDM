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
                <a href="{{ url('/admin/kegiatan') }}" class="btn btn-warning">Kembali</a>
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
                    <tr>
                        <th class="text-right col-3"> Draft Surat Tugas : </th>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" onclick="window.location.href='{{ route('admin.kegiatan.export_word', $kegiatan->id_kegiatan) }}'">Buat Draft Surat tugas</button>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Surat Tugas:</th>
                        <td>
                            <form id="uploadForm" enctype="multipart/form-data">
                                <div class="custom-file mb-2">
                                    <input type="file" name="dokumen" class="custom-file-input" id="draf_surat_tugas">
                                    <label class="custom-file-label" for="draf_surat_tugas">Draf Surat Tugas</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-warning mt-2" onclick="uploadSuratTugas()">Upload Surat Tugas</button>
                            </form>
                        </td>
                    </tr>
                </table>
                <div class="alert alert-info mt-3">
                    <h5><i class="icon fas fa-info"></i> Data Anggota</h5>
                    Berikut adalah anggota yang terlibat dalam kegiatan ini
                </div>
                <table class="table table-sm table-bordered table-stripped">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $a)
                            <tr>
                                <td class="text-center">{{ $a->user->nama }}</td>
                                <td class="text-center">{{ $a->jabatan->jabatan_nama }}</td>
                                <td class="text-center">{{ $a->jabatan->poin }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-right">
                    <button type="button" class="btn btn-primary" onclick="confirmKegiatan({{ $kegiatan->id_kegiatan }})">Konfirmasi</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endempty

@push('css')
@endpush

@push('js')
<script>
    function updateFileName() {
        var input = document.getElementById('draf_surat_tugas');
        var fileName = input.files[0].name;
        var label = document.getElementById('draf_surat_tugas_label');
        label.textContent = fileName;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        var dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.kegiatan.list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    // Add any additional parameters here if needed
                }
            },
            columns: [
                { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_mulai', name: 'tanggal_mulai', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_selesai', name: 'tanggal_selesai', className: "text-center", orderable: true, searchable: true },
                { data: 'pic', name: 'pic', className: "text-center", orderable: true, searchable: true },
                { data: 'status', name: 'status', className: "text-center", orderable: true, searchable: true },
                { data: 'poin_kegiatan', name: 'poin_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'surat_tugas', name: 'surat_tugas', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });
    });

    function uploadSuratTugas() {
        var formData = new FormData(document.getElementById('uploadForm'));
        var fileInput = document.getElementById('draf_surat_tugas');
        var file = fileInput.files[0];
        if (!file) {
            alert('Please select a file to upload.');
            return;
        }

        formData.append('dokumen', file);

        fetch('{{ route("admin.kegiatan.upload_surat_tugas", $kegiatan->id_kegiatan) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File uploaded successfully.');
            } else {
                alert('File upload failed.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the file.');
        });
    }

    function confirmKegiatan(id) {
        $.ajax({
            url: '{{ url("/admin/kegiatan") }}/' + id + '/confirm_ajax',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message
                    });
                }
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'An error occurred. Please try again.'
                });
            }
        });
    }
</script>
@endpush