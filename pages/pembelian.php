<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/pembelian.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/update/pembelian.php";

// Get selected month and year from request
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Fetch data using the general function
$data = Tampil_Data("pembelian");

// Filter data based on selected month and year if filter is applied
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        $date = DateTime::createFromFormat('Y-m-d', $item->tanggal);
        return $date->format('m') === $selectedMonth && $date->format('Y') === $selectedYear;
    });
} else {
    $filteredData = $data; // Show all data if no filter is applied
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Pembelian Peralatan</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Pembelian Peralatan</h4>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal" data-bs-target="#insertModal">Tambah Data</button>

                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover text-center">
                                <!-- Filter Form -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <form method="POST">
                                            <div class="input-group">
                                                <select name="month" class="form-select">
                                                    <option value="">Pilih Bulan</option>
                                                    <?php for ($m = 1; $m <= 12; $m++) { ?>
                                                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>><?= date("F", mktime(0, 0, 0, $m, 10)) ?></option>
                                                    <?php } ?>
                                                </select>
                                                <select name="year" class="form-select">
                                                    <option value="">Pilih Tahun</option>
                                                    <?php for ($y = date("Y") - 10; $y <= date("Y"); $y++) { ?>
                                                        <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                                    <?php } ?>
                                                </select>
                                                <button class="btn btn-primary" type="submit">Filter</button>
                                                <a href="" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Nama Supplier</th>
                                        <th>Total Biaya</th>
                                        <th>Detail</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (!empty($filteredData)) {
                                        foreach ($filteredData as $j) {
                                            $idPembelian = $j->id_pengeluaran;
                                            $tanggal = $j->tanggal;
                                            $namasupplier = $j->nama_supplier;
                                            $totalbiaya = $j->total_biaya;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $namasupplier ?></td>
                                                <td class="text-end">Rp. <?= number_format($j->total_biaya, 2, ',', '.') ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="detailModal" data-bs-toggle="modal" data-bs-target="#detailModalpembelian" data-idpkrja="<?= $idPembelian ?>">Detail</button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="updateModal" data-bs-toggle="modal" data-bs-target="#updateModalpembelian" data-idpkrja="<?= $idPembelian ?>">Update</button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data untuk bulan dan tahun yang dipilih.</td></tr>";
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
<div class="modal fade" id="detailModalpembelian" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                            <th>Harga Satuan</th>
                            <th>Bulan Ekonomis</th>
                            <th>Nilai Penyusutan</th>
                            <th>Akhir Periode Penyusutan</th>
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
            var varidPembelian = $(this).data('idpkrja');
            var varnamaPembelian = $(this).data('nmPembelian');
            var vardeskripsi = $(this).data('deskripsi');
            var varstatus = $(this).data('stts');
            var varnamakun = $(this).data('namaakun');

            $('#id_bhn_splr').val(varidPembelian);
            $('#nmplat').val(varnamaPembelian);
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
            var varidPengeluaran = $(this).data('idpkrja');

            // Mengambil detail transaksi berdasarkan ID
            $.ajax({
                url: 'webservice/api/detailpengeluaran.php',
                type: 'GET',
                data: {
                    id: varidPengeluaran
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_peralatan}</td>
                                <td>${item.jumlah}</td>
                                <td>${item.harga_satuan}</td>
                                <td>${item.bulan_ekonomis}</td>
                                <td>${item.nilai_penyusutan}</td>
                                <td>${item.akhir_periode_penyusutan}</td>
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