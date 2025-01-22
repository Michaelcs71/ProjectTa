<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
// Menghitung bulan lalu
$lastMonth = date('m', strtotime('-1 month'));
$lastYear = date('Y', strtotime('-1 month'));

// Mendapatkan nilai bulan dan tahun dari parameter POST
$month = isset($_GET['month']) && $_GET['month'] !== '' ? $_GET['month'] : $lastMonth;   // Default bulan lalu
$year = isset($_GET['year']) && $_GET['year'] !== '' ? $_GET['year'] : $lastYear;        // Default tahun bulan lalu

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
                                            <?php for ($y = date("Y") - 5; $y <= date("Y"); $y++) { ?>
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
                            // Formatkan angka dengan format 'Rp. 100.000,00' di sumbu Y
                            var numberFormat = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            return numberFormat.format(value).replace('Rp', 'Rp.'); // Menambahkan titik setelah 'Rp'
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            // Formatkan nilai di tooltip dengan format 'Rp. 100.000,00'
                            var numberFormat = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            return tooltipItem.dataset.label + ': ' + numberFormat.format(tooltipItem.raw).replace('Rp', 'Rp.');
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    const default_month = <?= json_encode($month) ?>; 
    const default_year = <?= json_encode($year) ?>;

    function getPreviousPeriod() {
        const today = new Date();
        today.setMonth(today.getMonth() - 1); // Pindahkan ke bulan sebelumnya
        const year = today.getFullYear();
        const month = (today.getMonth() + 1).toString().padStart(2, '0'); // Tambahkan 1 karena bulan dalam JavaScript dimulai dari 0
        return `${year}-${month}`;
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(angka);
    }

    function fetchData(keterangan, id) {
        const periode = getPreviousPeriod(); // Dapatkan periode bulan sebelumnya
        $.ajax({
            url: "webservice/api/totaldashboard.php",
            type: "GET",
            data: {
                periode: periode,
                keterangan: keterangan // Kirim keterangan ke API
            },
            dataType: "json",
            success: function(response) {
                // Memastikan response.data berisi data yang sesuai
                const data = response.data || [];
                let total = 0;
                // Cari total berdasarkan keterangan yang sesuai
                for (const item of data) {
                    if (item.keterangan === keterangan) {
                        total = item.total;
                    }
                }
                $(id).text(formatRupiah(total)); // Tampilkan hasil pada elemen yang sesuai
            },
            error: function(xhr, status, error) {
                console.error(`Error: ${status}, ${error}`);
                $(id).text("Gagal mengambil data: " + error); // Tampilkan pesan error jika gagal mengambil data
            }
        });
    }

    $(document).ready(function() {
        // Fungsi untuk mendapatkan data dari API
        function getData() {
            const penggunaan = [{
                    keterangan: "Total Pendapatan",
                    id: "#totalPendapatan"
                },
                {
                    keterangan: "Total HPP",
                    id: "#totalHpp"
                },
                {
                    keterangan: "Laba Rugi",
                    id: "#totalLabaRugi"
                }
            ];

            // Loop untuk mengambil data dari API untuk masing-masing keterangan
            for (const { keterangan, id } of penggunaan) {
                fetchData(keterangan, id); // Panggil dengan keterangan yang sesuai
            }
        }

        // Ambil data pertama kali saat halaman dimuat
        getData();

        // Update chart berdasarkan nilai default bulan dan tahun
        updateCharts(default_month, default_year);

        // Set interval untuk mengambil data setiap 30 detik
        setInterval(function() {
            getData();
        }, 30000); // 30 detik
    });

    // Fungsi untuk menangani form filter data
    document.getElementById("filterData").addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

        const month = formData.get('month') || default_month; // Gunakan month default jika tidak ada input
        const year = formData.get('year') || default_year; // Gunakan year default jika tidak ada input

        // Update chart dengan bulan dan tahun yang dipilih atau default
        updateCharts(month, year);
    });
</script>


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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>