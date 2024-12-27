<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/pendapatan.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/update/pendapatan.php";


if (function_exists('Tampil_Data')) {
    echo "Function Tampil_Data exists.";
} else {
    echo "Function Tampil_Data does not exist.";
}



// Debugging to ensure data fetch is correct
if ($data === null) {
    echo "Data is null.";
} else {
    echo "Data fetched successfully.";
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Kategori</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Kategori</h4>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModal">Tambah Data</button>

                            <table id="datatable-buttons"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama Platform</th>
                                        <th>Total Pendapatan</th>
                                        <th>Bulan Pendapatan</th>
                                        <th>Kode Akun</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = Tampil_Data("pendapatan");
                                    $no = 1;
                                    if ($data !== null) {
                                        foreach ($data as $j) {
                                            $idpendapatan = $j->id_pendapatan;
                                            $namakategori = $j->nama_platform;
                                            $deskripsi = $j->total_pendapatan;
                                            $status = $j->tanggal_pendapatan;
                                            $namaakun = $j->id_akun;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $namakategori ?></td>
                                                <td><?= $deskripsi ?></td>
                                                <td><?= $status ?></td>
                                                <td><?= $namaakun ?></td>
                                                <td>

                                                    <button type="button" class="btn btn-primary" id="detailModal"
                                                        data-bs-toggle="modal" data-bs-target="#detailModalPendapatan"
                                                        data-idpkrja="<?= $idpendapatan ?>">Detail</button>

                                                    <button type="button" class="btn btn-primary" id="updateModal"
                                                        data-bs-toggle="modal" data-bs-target="#updateModalPendapatan"
                                                        data-idpkrja="<?= $idpendapatan ?>" data-nmkategori="<?= $namakategori ?>" data-deskripsi="<?= $deskripsi ?>"
                                                        data-stts="<?= $status ?>" data-namaakun="<?= $namaakun ?>">Update</button>

                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
</div>

<div class="modal fade" id="detailModalPendapatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody id="detail_data_pendapatan">
                        <!-- Data akan diisi oleh AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#updateModal', function() {
            var varidpendapatan = $(this).data('idpkrja');
            var varnamakategori = $(this).data('nmkategori');
            var vardeskripsi = $(this).data('deskripsi');
            var varstatus = $(this).data('stts');
            var varnamakun = $(this).data('namaakun');

            $('#id_bhn_splr').val(varidpendapatan);
            $('#nmplat').val(varnamakategori);
            $('#totalpend').val(vardeskripsi);
            $('#tglpend').val(varstatus);
            $('#nmakun').val(varnamakun);

        });

        $(document).on('click', '#deleteConfirmation', function() {
            var kdpesnan = $(this).data('kdpsn');
            Swal.fire({
                title: "Apa anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2ab57d",
                cancelButtonColor: "#fd625e",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batalkan",
            }).then(function(result) {
                if (result.isConfirmed) {
                    location.assign("<?= $baseURL ?>/index.php?link=laundry_pesanan&aksi=delete&id=" + kdpesnan);
                }
            });
        });

        $(document).on('click', '#detailModal', function() {
            var varidpendapatan = $(this).data('idpkrja'); // Ambil data ID


            // Mengambil detail transaksi berdasarkan ID
            $.ajax({
                url: 'webservice/api/detailpendapatan.php',
                type: 'GET',
                data: {
                    id: varidpendapatan // Kirim ID ke API
                },
                success: function(response) {
                    console.log(response); // Debug respon dari API
                    var data = JSON.parse(response);
                    var rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nama_barang}</td>
                        <td>${item.total_barang}</td>
                    </tr>
                `;
                    });
                    $('#detail_data_pendapatan').html(rows);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });


    });
</script>