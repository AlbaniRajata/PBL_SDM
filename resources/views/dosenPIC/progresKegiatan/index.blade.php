@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ $breadcrumb->title }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover table-sm" id="progress-kegiatan-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">No</th>
                            <th style="width: 50%;" class="text-center">Nama Kegiatan</th>
                            <th style="width: 30%;" class="text-center">Presentase</th>
                            <th style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($progresKegiatan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->nama_kegiatan }}</td>
                                <td class="text-center">{{ $item->progress }}%</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" onclick="editProgresKegiatan({{ $item->id_kegiatan }})">Edit</button>
                                    <button class="btn btn-sm btn-info" onclick="detailProgresKegiatan({{ $item->id_kegiatan }})">Detail</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Edit -->
    <div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Edit Progress Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="progressForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_kegiatan">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="progress">Presentase</label>
                            <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" required>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Progress Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="detailForm">
                        <div class="form-group">
                            <label for="detail_nama_kegiatan">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="detail_nama_kegiatan" name="detail_nama_kegiatan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="detail_progress">Presentase</label>
                            <input type="number" class="form-control" id="detail_progress" name="detail_progress" readonly>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editProgressKegiatan(id) {
            $.get("{{ route('progresKegiatan.edit', '') }}/" + id, function(data) {
                if (data.status) {
                    $('#progressModalLabel').text('Edit Progress Kegiatan');
                    $('#progressForm').attr('action', "{{ route('progresKegiatan.update', '') }}/" + id);
                    $('#progressForm').find('input[name="_method"]').val('PATCH');
                    $('#nama_kegiatan').val(data.data.nama_kegiatan);
                    $('#progress').val(data.data.progress);
                    $('#progressModal').modal('show');
                } else {
                    alert(data.message);
                }
            });
        }

        function detailProgressKegiatan(id) {
            $.get("{{ route('progresKegiatan.detail', '') }}/" + id, function(data) {
                if (data.status) {
                    $('#detailModalLabel').text('Detail Progress Kegiatan');
                    $('#detail_nama_kegiatan').val(data.data.nama_kegiatan);
                    $('#detail_progress').val(data.data.progress);
                    $('#detailModal').modal('show');
                } else {
                    alert(data.message);
                }
            });
        }

        $('#progressForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(data) {
                    if (data.status) {
                        $('#progressModal').modal('hide');
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                }
            });
        });
    </script>
@endsection
