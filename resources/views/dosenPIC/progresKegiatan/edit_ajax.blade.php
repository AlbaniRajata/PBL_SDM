<form id="form-edit-progress" action="{{ url('/dosenPIC/progresKegiatan/'.$kegiatan->id_kegiatan.'/update' ) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Progress Kegiatan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Nama Kegiatan</label>
            <input type="text" class="form-control" value="{{ $kegiatan->nama_kegiatan }}" readonly>
        </div>
        <div class="form-group">
            <label for="progress">Progress Kegiatan (%)</label>
            <input 
                type="number" 
                name="progress" 
                id="progress" 
                class="form-control" 
                value="{{ $kegiatan->progress }}" 
                min="0" 
                max="100" 
                required
            >
            <small class="form-text text-muted">
                Masukkan progress antara 0-100%
            </small>
        </div>
        <div class="text-right">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#form-edit-progress').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST', // Ubah ke POST karena Laravel memerlukan _method PATCH
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });

                    $('#myModal').modal('hide');
                    $('#kegiatan-table').DataTable().ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status) {
                    errorMessage = `Error ${xhr.status}: ${xhr.statusText}`;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: errorMessage
                });
            }
        });
    });
});
</script>