<?php
ob_start();
session_start();
// include "webservices/config.php";
// include "lib/function.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<?php
if (isset($_GET['link'])) {
    if (($_GET['link']) == 'dashboard') {
        $title = "Dashboard | ";
    } elseif (($_GET['link']) == 'login') {
        $title = "Form Login | ";
    } elseif (($_GET['link']) == 'pendapatan') {
        $title = "Transaksi Pendapatan | ";
    } elseif (($_GET['link']) == 'pembelian') {
        $title = "Transaksi Peralatan | ";
    } elseif (($_GET['link']) == 'bop') {
        $title = "Transaksi Biaya Overhead | ";
    } elseif (($_GET['link']) == 'stokmaterial') {
        $title = "Stok Bahan Material | ";
    } elseif (($_GET['link']) == 'stokbarang') {
        $title = "Stok Barang Jadi | ";
    } elseif (($_GET['link']) == 'hpp') {
        $title = "Laporan Harga Pokok Penjualan | ";
    } elseif (($_GET['link']) == 'labarugi') {
        $title = "Laporan Laba Rugi | ";
    } elseif (($_GET['link']) == 'pekerja') {
        $title = "Master Pekerja | ";
    } elseif (($_GET['link']) == 'supplier') {
        $title = "Master Supplier | ";
    } elseif (($_GET['link']) == 'akun') {
        $title = "Master Akun | ";
    } elseif (($_GET['link']) == 'kategori') {
        $title = "Master Kategori | ";
    } elseif (($_GET['link']) == 'platform') {
        $title = "Master Platform | ";
    } elseif (($_GET['link']) == 'bahanmaterial') {
        $title = "Master Bahan Material | ";
    } elseif (($_GET['link']) == 'barangajadi') {
        $title = "Master Barang Jadi | ";
    } elseif (($_GET['link']) == 'satuan') {
        $title = "Master Satuan | ";
    } elseif (($_GET['link']) == 'peralatan') {
        $title = "Master Peralatan | ";
    } elseif (($_GET['link']) == 'overhead') {
        $title = "Master Nama Overhead | ";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... existing head content ... -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- ... existing body content ... -->

    <?php
    if (isset($_SESSION['error_message'])) {
        echo "<script>
                Swal.fire({
                    title: 'Gagal Update',
                    text: '" . $_SESSION['error_message'] . "',
                    icon: 'error',
                    confirmButtonColor: '#2ab57d',
                    confirmButtonText: 'OK'
                });
            </script>";
        unset($_SESSION['error_message']); // Clear the error message
    }
    ?>

    <!-- ... existing body content ... -->
</body>

</html>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>
        <?= $title; ?>Sistem Informasi Keuangan
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- app favicon -->
    <link rel="icon" href="assets/images/icon.png">

    <!-- plugin css -->
    <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet"
        type="text/css" />

    <!-- preloader css -->
    <link rel="stylesheet" href="assets/css/preloader.min.css" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Dropzone css -->
    <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

    <!-- CSS Sweet alert -->
    <link href="assets/css/sweetalert2.min.css" rel="stylesheet" type="text/css">

    <!-- app Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <!-- choices css -->
    <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />

    <!-- color picker css -->
    <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/classic.min.css" /> <!-- 'classic' theme -->
    <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/monolith.min.css" /> <!-- 'monolith' theme -->
    <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/nano.min.css" /> <!-- 'nano' theme -->

    <!-- datepicker css -->
    <link rel="stylesheet" href="assets/libs/flatpickr/flatpickr.min.css">

    <!-- CSS Custom by SI UMC -->
    <link rel="stylesheet" href="assets/css/customcss.css" type="text/css">
    <link rel="stylesheet" href="assets/css/iconstyle.css" type="text/css">
    <link rel="stylesheet" href="assets/css/ionicons.min.css" type="text/css">

    <!-- JQUERY -->
    <script src="assets/libs/jquery/jquery.min.js"></script>

    <!-- Jquery Customm by SI UMC -->
    <script src="lib/jsFunction.js"></script>
    <script src="assets/libs/chart.js/chartbar.js"></script>







</head>

<body>


    <?php


    if (isset($_GET['link']) && $_GET['link'] == 'login') {
        include 'login.php';
        exit();
    }

    if (!isset($_SESSION['admin']) && !isset($_SESSION['manager']) && !isset($_SESSION['accounting'])) {
        header("Location: index.php?link=login");
        exit();
    }
    include 'menu/sidebar.php';
    include 'menu/header.php';


    if (isset($_GET['link'])) {
        // menampilkan page dashboard
        if ($_GET['link'] == 'dashboard') {
            include "pages/dashboard.php";
            // Tidak perlu pengalihan setelah include
        } elseif ($_GET['link'] == 'pendapatan') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/pendapatan.php";
            }
        } elseif ($_GET['link'] == 'pembelian') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/pembelian.php";
            }
        } elseif ($_GET['link'] == 'penggajian') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/penggajian.php";
            }
        } elseif ($_GET['link'] == 'stokmaterial') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/stokmaterial.php";
            }
        } elseif ($_GET['link'] == 'stokbarang') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/stokbarang.php";
            }
        } elseif ($_GET['link'] == 'stokperlengkapan') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/stokperlengkapan.php";
            }
        } elseif ($_GET['link'] == 'bop') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_karyawan WHERE id_karyawan = '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=pendapatan<br>";
                            header("Location: " . $baseURL . "/index.php?link=pendapatan");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "pages/bop.php";
            }
        } elseif ($_GET['link'] == 'pekerja') {
            include "pages/master/pekerja.php";
        } elseif ($_GET['link'] == 'supplier') {
            include "pages/master/supplier.php";
        } elseif ($_GET['link'] == 'kategori') {
            include "pages/master/kategori.php";
        } elseif ($_GET['link'] == 'platform') {
            include "pages/master/platform.php";
        } elseif ($_GET['link'] == 'bahanmaterial') {
            include "pages/master/bahanmaterial.php";
        } elseif ($_GET['link'] == 'barangjadi') {
            include "pages/master/barangjadi.php";
        } elseif ($_GET['link'] == 'satuan') {
            include "pages/master/satuan.php";
        } elseif ($_GET['link'] == 'akun') {
            include "pages/master/akun.php";
        } elseif ($_GET['link'] == 'peralatan') {
            include "pages/master/peralatan.php";
        } elseif ($_GET['link'] == 'perlengkapan') {
            include "pages/master/perlengkapan.php";
        } elseif ($_GET['link'] == 'peralatan') {
            include "pages/master/peralatan.php";
        } elseif ($_GET['link'] == 'overhead') {
            include "pages/master/overhead.php";
        } elseif ($_GET['link'] == 'hpp') {
            include "pages/hpp.php";
        } elseif ($_GET['link'] == 'detailhpp') {
            include "pages/detailhpp.php";
        } elseif ($_GET['link'] == 'labarugi') {
            include "pages/labarugi.php";
        } elseif ($_GET['link'] == 'jurnal') {
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'delete') {
                    $id = $_GET['id'];
                    if (!empty($id)) {
                        // Debugging: Check the ID
                        echo "ID to delete: " . $id . "<br>";
                        $query = mysqli_query($koneksi, "DELETE FROM master_akun WHERE no_akun= '$id'");
                        if ($query) {
                            // Debugging: Check the URL before redirecting
                            echo "Record deleted successfully. Redirecting to: " . $baseURL . "/index.php?link=jurnal<br>";
                            header("Location: " . $baseURL . "/index.php?link=jurnal");
                            exit;
                        } else {
                            echo "Error deleting record: " . mysqli_error($koneksi);
                        }
                    } else {
                        echo "ID is missing.";
                    }
                }
            } else {
                include "jurnalumum.php";
            }
        } elseif ($_GET['link'] == 'register') {
            if (isset($_GET['aksi'])) {
            } else {
                include "register.php";
            }
        }
    } else {

        include 'login.php';
        header("Location: " . $baseURL . "/index.php?link=login");
    }

    ob_end_flush();
    ?>
</body>



</html>

<!-- JaVASCRIPT -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/metismenu/metisMenu.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script>
    feather.replace()
</script>

<!-- choices js -->
<script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- init js -->
<script src="assets/js/pages/form-advanced.init.js"></script>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- apexcharts init -->
<script src="assets/js/pages/apexcharts.init.js"></script>

<!-- Calendar init -->
<script src="assets/js/pages/calendar.init.js"></script>

<!-- Plugins js-->
<script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- dashboard init -->
<script src="assets/js/pages/dashboard.init.js"></script>

<!-- Required datatable js -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/js/pages/sweetalert.init.js"></script>


<!-- Responsive examples -->
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="assets/js/pages/datatables.init.js"></script>

<!-- pace js -->
<script src="assets/libs/pace-js/pace.min.js"></script>

<script src="assets/libs/@fullcalendar/core/main.min.js"></script>
<script src="assets/libs/@fullcalendar/bootstrap/main.min.js"></script>
<script src="assets/libs/@fullcalendar/daygrid/main.min.js"></script>
<script src="assets/libs/@fullcalendar/timegrid/main.min.js"></script>
<script src="assets/libs/@fullcalendar/interaction/main.min.js"></script>

<!-- Sweet alert 2 -->
<script src="assets/js/pages/sweetalert2.all.min.js"></script>

<!-- Chart JS -->
<script src="assets/libs/chart.js/Chart.bundle.min.js"></script>
<!-- chartjs init -->
<script src="assets/js/pages/chartjs.init.js"></script>

<!-- ehartjs init -->
<script src="assets/js/pages/echarts.min.js"></script>
<script src="assets/js/pages/echarts.init.js"></script>

<!-- dropzone js -->
<script src="assets/libs/dropzone/min/dropzone.min.js"></script>

<!-- twitter-bootstrap-wizard js -->
<script src="assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script src="assets/libs/twitter-bootstrap-wizard/prettify.js"></script>

<!-- form wizard init -->
<script src="assets/js/pages/form-wizard.init.js"></script>

<script src="assets/js/app.js"></script>
<script>
    $('#tablecustom').DataTable({
        dom: 'Bfrtip',
        "scrollX": true,
    });
</script>