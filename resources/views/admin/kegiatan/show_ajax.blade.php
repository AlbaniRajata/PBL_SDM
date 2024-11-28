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
                </table>

                <!-- Formulir Upload File -->
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Upload File:</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id_kegiatan }}">
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" onclick="uploadSuratTugas()">Upload</button>
                    </div>
                </form>

                <!-- Display Uploaded Documents -->
                <div class="mt-3">
                    <h5><i class="icon fas fa-file"></i> Dokumen yang Diunggah</h5>
                    <ul id="uploaded-documents">
                        @foreach ($kegiatan->dokumen as $dokumen)
                            <li><a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank">{{ $dokumen->nama_dokumen }}</a></li>
                        @endforeach
                    </ul>
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
        var input = document.getElementById('file');
        var fileName = input.files[0].name;
        var label = document.getElementById('file_label');
        label.textContent = fileName;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function uploadSuratTugas() {
        var formData = new FormData(document.getElementById('uploadForm'));
        var fileInput = document.getElementById('file');
        var file = fileInput.files[0];
        if (!file) {
            alert('Please select a file to upload.');
            return;
        }

        formData.append('file', file);

        fetch('{{ route("kegiatan.upload") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'File berhasil diupload.'
                });
                // Append the new document to the list
                var uploadedDocuments = document.getElementById('uploaded-documents');
                var newDocument = document.createElement('li');
                newDocument.innerHTML = '<a href="{{ asset('storage') }}/' + data.file_path + '" target="_blank">' + data.nama_dokumen + '</a>';
                uploadedDocuments.appendChild(newDocument);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengupload file.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'An error occurred while uploading the file.'
            });
        });
    }
</script>
@endpush