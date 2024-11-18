<div id="modal-master" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengguna</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(!$user)
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                        Data yang anda cari tidak ditemukan
                    </div>
                    <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
                @else
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">ID :</th>
                            <td class="col-9">{{ $user->id_user }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Username :</th>
                            <td class="col-9">{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama :</th>
                            <td class="col-9">{{ $user->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Lahir :</th>
                            <td class="col-9">{{ $user->tanggal_lahir->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Email :</th>
                            <td class="col-9">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">NIP :</th>
                            <td class="col-9">{{ $user->NIP }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Level :</th>
                            <td class="col-9">{{ $user->level }}</td>
                        </tr>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-header.bg-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }
    .modal-header .close {
        color: #fff;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>