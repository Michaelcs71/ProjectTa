<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";

// Get selected month and year from request
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Fetch data using the general function
$data = Tampil_Data("detailhpp");

// Filter data based on selected month and year if filter is applied
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        $date = DateTime::createFromFormat('Y-m', $item->periode); // Ensure correct date format
        if ($date) {
            return $date->format('m') === $selectedMonth && $date->format('Y') === $selectedYear;
        }
        return false;
    });
} else {
    $filteredData = $data; // Show all data if no filter is applied
}
?>

<div class="main-content bg">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div class="marquee">
                <p>Selamat Datang di Sistem Keuangan Sevenshop</p>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="small-box bg-green text-white shadow-primary">
                        <div class="inner">
                            <h1 class="text-white" id="totalPendapatan">Rp 0</h1>
                            <p>Total Pendapatan</p>
                        </div>
                        <a class="small-box-footer text-white bg-footer" href="#">
                            Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="small-box bg-red text-white shadow-primary">
                        <div class="inner">
                            <h1 class="text-white" id="totalHpp">Rp 0</h1>
                            <p>Total Biaya Produksi</p>
                        </div>
                        <a class="small-box-footer text-white bg-footer" href="#">
                            Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="small-box bg-yellow text-white shadow-primary">
                        <div class="inner">
                            <h1 class="text-white" id="totalLabaRugi">Loading...</h1>
                            <p>Jumlah Laba Rugi</p>
                        </div>
                        <a class="small-box-footer text-white text-center bg-footer" data-bs-toggle="modal"
                            data-bs-target="#modalViewKelahiran">Selengkapnya
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">HPP Per Unit</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <select name="month" class="form-select">
                                            <option value="">Pilih Bulan</option>
                                            <?php for ($m = 1; $m <= 12; $m++) { ?>
                                                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                                    <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="year" class="form-select">
                                            <option value="">Pilih Tahun</option>
                                            <?php for ($y = date("Y") - 10; $y <= date("Y"); $y++) { ?>
                                                <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" type="submit">Filter</button>
                                        <a href="" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <canvas id="hppChart"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                <?php
                                // Prepare data for the chart
                                $labels = [];
                                $hppData = [];
                                foreach ($filteredData as $item) {
                                    $labels[] = $item->nama_barang;
                                    $hppData[] = (float) $item->hpp_per_unit;
                                }
                                ?>

                                var ctx = document.getElementById('hppChart').getContext('2d');
                                var hppChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: <?= json_encode($labels) ?>,
                                        datasets: [{
                                            label: 'Total HPP Per Unit',
                                            data: <?= json_encode($hppData) ?>,
                                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        return 'Rp. ' + value.toLocaleString();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            </script>

                        </div>
                    </div><!--end card-->
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Pendapatan Per Bulan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div><!--end card-->
                </div>
                <!-- end row -->
            </div> <!-- container-fluid -->

        </div>
    </div>
</div>

<script>
    const apiUrll = "http://localhost/ProjectTa/webservice/api/labarugidashboard.php";
    // Ambil data dari API
    fetch(apiUrll)
        .then(response => response.json())
        .then(data => {
            // Memproses data dari API
            const labels = data.map(item => item.periode); // Periode sebagai label
            const labaRugiBersih = data.map(item => item.laba_rugi_bersih); // Laba bersih sebagai data

            // Membuat Line Chart
            const ctx = document.getElementById('lineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Laba Bersih (Rp)',
                        data: labaRugiBersih,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(tooltipItem.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Periode'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Laba Bersih (Rp)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(value);
                                },
                                beginAtZero: true
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
</script>




<link rel="stylesheet" href="assets/libs/glightbox/css/glightbox.min.css">
<script src="assets/libs/glightbox/js/glightbox.min.js"></script>
<script src="assets/js/pages/lightbox.init.js"></script>