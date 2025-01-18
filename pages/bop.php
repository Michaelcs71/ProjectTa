<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/bop.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/update/bop.php";

$data = Tampil_Data("bop");

// Get selected month and year from request
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Ensure $data is an array
if (!is_array($data)) {
    $data = []; // Default to empty array if $data is null or not an array
}

// Filter data if month and year are selected
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        $date = DateTime::createFromFormat('Y-m-d', $item->tanggal);
        return $date && $date->format('m') === $selectedMonth && $date->format('Y') === $selectedYear;
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title font-size-18">Data Biaya Overhead Tes</h4>
                        </div>
                        <div class="card-body">
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
                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModal">Tambah Data</button>
                            <table id="datatable"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover text-center">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Total Biaya</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (!empty($filteredData)) {
                                        foreach ($filteredData as $j) { // Use $filteredData here
                                            $idperlengkapan = $j->id_pengeluaran_overhead;
                                            $tanggal = $j->tanggal;
                                            $totalbiaya = $j->total;
                                            $status = $j->status;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td class="text-end">Rp. <?= number_format($j->total, 2, ',', '.') ?></td>
                                                <td><?= $status ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="detailModal"
                                                        data-bs-toggle="modal" data-bs-target="#detailModalperlengkapan"
                                                        data-idpkrja="<?= $idperlengkapan ?>">Detail</button>

                                                    <button type="button" class="btn btn-primary" id="updateModal"
                                                        data-bs-toggle="modal" data-bs-target="#updateModalperlengkapan"
                                                        data-idpkrja="<?= $idperlengkapan ?>" data-nmperlengkapan="<?= $namaperlengkapan ?>" data-deskripsi="<?= $deskripsi ?>"
                                                        data-stts="<?= $status ?>" data-namaakun="<?= $namaakun ?>">Update</button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">Tidak ada data ditemukan untuk bulan dan tahun yang dipilih.</td>
                                        </tr>
                                    <?php
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
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Biaya Overhead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Keterangan</th>
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




        $(document).on('click', '#detailModal', function() {
            var varidoverhead = $(this).data('idpkrja');

            // Mengambil detail transaksi berdasarkan ID
            $.ajax({
                url: 'webservice/api/detailbop.php',
                type: 'GET',
                data: {
                    id: varidoverhead
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var rows = '';
                    var currencyFormat = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    });

                    if (data.length > 0) {
                        data.forEach(function(item, index) {
                            rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_overhead}</td>
                                <td class="text-end">${currencyFormat.format(item.biaya_overhead)}</td>
                                <td>${item.keterangan}</td>
                            </tr>
                        `;
                        });
                    } else {
                        rows = `<tr><td colspan="5" class="text-center">Tidak ada data detail.</td></tr>`;
                    }

                    $('#detail_data_pengeluaran').html(rows);
                },
                error: function() {
                    $('#detail_data_pengeluaran').html('<tr><td colspan="3" class="text-center">Error saat mengambil data.</td></tr>');
                }
            });
        });
    });
</script>