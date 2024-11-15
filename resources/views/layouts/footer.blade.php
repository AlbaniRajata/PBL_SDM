<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <style>
      img.image {
          width: auto;
          height: 125px;
          max-width: 150px;
          max-height: 150px;
          margin-top: 10px;
          margin-bottom: 10px;
      }
      .modal-dialog {
          max-width: 700px;
          border-radius: 8px;
      }
      .modal-content {
          border-radius: 8px;
          overflow: hidden;
          border: none;
      }
      .modal-header {
          background: linear-gradient(45deg, #007bff, #0056b3);
          color: #fff;
          text-align: center;
      }
      .modal-title {
          font-weight: bold;
          text-align: center;
          font-size: 22px;
      }
      .modal-body {
          padding: 20px;
          font-size: 16px;
          color: #333;
          background-color: #f7f9fc;
      }
      .modal-footer {
          border-top: none;
          text-align: center;
          background-color: #f7f9fc;
      }
      .modal-footer .btn {
          background-color: #00468c;
          color: #fff;
          border: none;
          padding: 8px 20px;
          border-radius: 10px;
          transition: background-color 0.3s ease;
      }
      .modal-footer .btn:hover {
          background-color: #002f5a;
      }
      .centered-section {
          text-align: center;
          margin-bottom: 15px;
          line-height: 1.2;
          font-size: 20px;
          color: #00468c;
      }
      .modal-body p {
          margin: 8px 0;
      }
      .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 123, 255, 0.05);
    }
  </style>
</head>

<footer class="main-footer">
  <div class="float-right d-none d-sm-block">
    <b>Version</b> 1.0.0
  </div>
  <strong>&copy; 2024-2025 <a href="#" id="info-developer-link">Kelompok 1-SIB3E</a>. All rights reserved.</strong>
</footer>

<!-- Modal Pop-up -->
<div class="modal fade" id="infoDeveloperModal" tabindex="-1" role="dialog" aria-labelledby="infoDeveloperModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoDeveloperModalLabel">Info Developer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <img class="image" src="{{ asset('polinema.png') }}" alt="Polinema Logo">
        </div>
        <div class="centered-section">
          <p><strong>SISTEM MANAJEMEN SUMBERDAYA MANUSIA</strong></p>
          <p><strong>Jurusan Teknologi Informasi</strong></p>
          <p><strong>Politeknik Negeri Malang</strong></p>
        </div>
        <table class="table table-sm table-bordered table-striped">
          <tbody>
            <tr>
              <td><strong>Ketua Pelaksana:</strong> Rokhimatul Wakhidah, S.Pd., M.T.</td>
            </tr>
            <tr>
              <td><strong>Anggota:</strong></td>
            </tr>
            <tr>
              <td>
                <ul>
                  <li>Albani Rajata Malik</li>
                  <li>Moch Fikri Setiawan</li>
                  <li>Nurhidayah</li>
                  <li>Sofi Lailatul Aniftasari</li>
                  <li>Yunika Puteri Dwi Antika</li>
                </ul>
              </td>
            </tr>
            <tr>
              <td><strong>Pembantu Pelaksana (Programmer):</strong></td>
            </tr>
            <tr>
              <td>
                <ul>
                  <li>Albani Rajata Malik</li>
                  <li>Moch Fikri Setiawan</li>
                  <li>Nurhidayah</li>
                  <li>Sofi Lailatul Aniftasari</li>
                  <li>Yunika Puteri Dwi Antika</li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Keluar</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AJAX and Modal Trigger Script -->
<script>
  $(document).ready(function(){
    // Event listener for the footer link
    $('#info-developer-link').on('click', function(e){
      e.preventDefault();
      // Show the modal
      $('#infoDeveloperModal').modal('show');
    });
  });
</script>
