<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/stokbarang.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/update/stokbarang.php";


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
                        <h4 class="mb-sm-0 font-size-18">Data Stok Barang Jadi</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Stok Barang Jadi</h4>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModalStokBarang">Stok Masuk</button>
                            <!-- <button type="button" class="btn btn-danger mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModalKeluar">Stok Keluar</button> -->
                            <table id="datatable-buttons"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama Pekerja</th>
                                        <th>Tanggal </th>
                                        <th>Total Upah</th>
                                        <th>Keterangan</th>
                                        <th>Kode Akun</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = Tampil_Data("stokbarang");
                                    $no = 1;
                                    if ($data !== null) {
                                        foreach ($data as $j) {
                                            $idstokbarang = $j->id_barang_masuk;
                                            $namapekerja = $j->nama_pekerja;
                                            $tanggal = $j->tanggal;
                                            $totalupah = $j->total_upah;
                                            $keterangan = $j->keterangan;
                                            $idakun = $j->id_akun;

                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $namapekerja ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $totalupah ?></td>
                                                <td><?= $keterangan ?></td>
                                                <td><?= $idakun ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-success" id="detailModal"
                                                        data-bs-toggle="modal" data-bs-target="#detailModalstokbarang"
                                                        data-idstokbarang="<?= $idstokbarang ?>">Detail</button>

                                                    <button type="button" class="btn btn-warning" id="updateModal">Update</button>
                                                    <button type="button" class="btn btn-danger" id="updateModal">Hapus</button>
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
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModalstokbarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data stokbarang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody id="detail_stok_barangjadi">
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
            var varidstokbarang = $(this).data('idstokbarang');
            var varnamastokbarang = $(this).data('nmstokbarang');
            var vardeskripsi = $(this).data('deskripsi');
            var varstatus = $(this).data('stts');
            var varnamakun = $(this).data('namaakun');

            $('#id_bhn_splr').val(varidstokbarang);
            $('#nmplat').val(varnamastokbarang);
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
            var varidstokbarang = $(this).data('idstokbarang');

            // Mengambil detail transaksi berdasarkan ID
            $.ajax({
                url: 'webservice/api/detailbarangjadi.php',
                type: 'GET',
                data: {
                    id: varidstokbarang
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_barang}</td>
                                <td>${item.jumlah}</td>
                                <td>${item.subtotal_upah}</td>
                            </tr>
                        `;
                    });
                    $('#detail_stok_barangjadi').html(rows);
                }
            });
        });
    });
</script>