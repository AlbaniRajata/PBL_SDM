<form action="{{ url('/kegiatan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input value="" type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control" required>
                        <option value="">- Pilih Jenis Kegiatan -</option>
                        @foreach ($jenis_kegiatan as $item)
                            <option value="{{ $item->id_jenis_kegiatan }}">{{ $item->nama_jenis_kegiatan }}</option>
                        @endforeach
                    </select>
                    <small id="error-jenis_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Acara</label>
                    <input value="" type="datetime-local" name="tanggal_acara" id="tanggal_acara" class="form-control" required>
                    <small id="error-tanggal_acara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input value="" type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input value="" type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tempat Acara</label>
                    <input value="" type="text" name="tempat_acara" id="tempat_acara" class="form-control" required>
                    <small id="error-tempat_acara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>PIC</label>
                    <input value="" type="text" name="pic" id="pic" class="form-control" required>
                    <small id="error-pic" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Poin</label>
                    <input value="" type="number" name="poin" id="poin" class="form-control" required>
                    <small id="error-poin" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Surat Tugas</label>
                    <input value="" type="text" name="surat_tugas" id="surat_tugas" class="form-control" required>
                    <small id="error-surat_tugas" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>