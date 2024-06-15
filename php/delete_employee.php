<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'config.php';
// Mendapatkan ID karyawan dari parameter URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Redirect ke dashboard jika ID karyawan tidak tersedia
    header('Location: dashboard.php');
    exit();
}
// Query untuk menghapus data gaji dari tabel salary yang terkait dengan karyawan
$query_delete_salary = "DELETE FROM salary WHERE employee_id=$id";
if (mysqli_query($conn, $query_delete_salary)) {
    // Operasi penghapusan berhasil
    $message = "Data gaji berhasil dihapus";
    $_SESSION['success_message'] = $message;
} else {
    // Operasi penghapusan gagal
    $error = mysqli_error($conn);
    $_SESSION['error_message'] = "Error: " . $error;
}
// Query untuk menghapus data karyawan dari tabel employee
$query_delete_employee = "DELETE FROM employee WHERE employee_id=$id";
if (mysqli_query($conn, $query_delete_employee)) {
    // Operasi penghapusan berhasil
    $message = "Data karyawan berhasil dihapus";
    $_SESSION['success_message'] = $message;
} else {
    // Operasi penghapusan gagal
    $error = mysqli_error($conn);
    $_SESSION['error_message'] = "Error: " . $error;
}
// Redirect kembali ke dashboard setelah penghapusan berhasil atau gagal
header('Location: dashboard.php');
exit();

