<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/stokperlengkapan.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/update/stokperlengkapan.php";


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
                        <h4 class="mb-sm-0 font-size-18">Data Perlengkapan</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Perlengkapan</h4>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModal">Tambah Data</button>

                            <table id="datatable-buttons"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Nama Supplier</th>
                                        <th>Total Biaya</th>
                                        <th>Kode Akun</th>
                                        <th>Detail</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = Tampil_Data("stokperlengkapan");
                                    $no = 1;
                                    if ($data !== null) {
                                        foreach ($data as $j) {
                                            $idperlengkapan = $j->id_pembelian_perlengkapan;
                                            $tanggal = $j->tanggal;
                                            $namasupplier = $j->nama_supplier;
                                            $totalbiaya = $j->total;
                                            $namaakun = $j->nama_akun;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $namasupplier ?></td>
                                                <td><?= $totalbiaya ?></td>
                                                <td><?= $namaakun ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="detailModal"
                                                        data-bs-toggle="modal" data-bs-target="#detailModalperlengkapan"
                                                        data-idpkrja="<?= $idperlengkapan ?>">Detail</button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="updateModal"
                                                        data-bs-toggle="modal" data-bs-target="#updateModalperlengkapan"
                                                        data-idpkrja="<?= $idperlengkapan ?>" data-nmperlengkapan="<?= $namaperlengkapan ?>" data-deskripsi="<?= $deskripsi ?>"
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

<!-- Detail Modal -->
<div class="modal fade" id="detailModalperlengkapan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data perlengkapan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody id="detail_data_pengeluaran">
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
            var varidstokperlengkapan = $(this).data('idpkrja');
            var varnamaperlengkapan = $(this).data('nmperlengkapan');
            var vardeskripsi = $(this).data('deskripsi');
            var varstatus = $(this).data('stts');
            var varnamakun = $(this).data('namaakun');

            $('#id_bhn_splr').val(varidstokperlengkapan);
            $('#nmplat').val(varnamaperlengkapan);
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
            var varidstokperlengkapan = $(this).data('idpkrja');

            // Mengambil detail transaksi berdasarkan ID
            $.ajax({
                url: 'webservice/api/detailperlengkapan.php',
                type: 'GET',
                data: {
                    id: varidstokperlengkapan
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_perlengkapan}</td>
                                <td>${item.jumlah}</td>
                                <td>${item.harga_satuan}</td>
                                <td>${item.sub_total}</td>
                            </tr>
                        `;
                    });
                    $('#detail_data_pengeluaran').html(rows);
                }
            });
        });
    });
</script>