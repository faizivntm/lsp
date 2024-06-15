<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $basic_salary = $_POST['basic_salary'];
    $nip = $_POST['nip'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // Insert data karyawan ke dalam tabel employee
    $query_employee = "INSERT INTO employee (name, position, basic_salary, NIP, Alamat, Tanggal_Lahir, Tanggal_Masuk_Kerja) 
  VALUES ('$name', '$position', $basic_salary, '$nip', '$alamat', '$tanggal_lahir', '$tanggal_masuk')";
    $insert_employee = mysqli_query($conn, $query_employee);

    if ($insert_employee) {
        // Dapatkan ID karyawan yang baru saja dimasukkan
        $employee_id = mysqli_insert_id($conn);

        // Hitung bonus sesuai dengan jabatan
        $bonus = 0;
        if ($position == "Manager") {
            $bonus = $basic_salary * 0.5;
        } elseif ($position == "Supervisor") {
            $bonus = $basic_salary * 0.4;
        } elseif ($position == "Staff") {
            $bonus = $basic_salary * 0.3;
        }

        // Hitung PPH
        $pph = ($basic_salary + $bonus) * 0.05;

        // Hitung total gaji
        $total_salary = $basic_salary + $bonus - $pph;

        // Mendapatkan tanggal saat ini
        $salary_date = date("Y-m-d");

        // Insert data gaji ke dalam tabel salary
        $query_salary = "INSERT INTO salary (employee_id, salary_date, bonus_salary, pph, total_salary) VALUES ($employee_id, '$salary_date', $bonus, $pph, $total_salary)";
        $insert_salary = mysqli_query($conn, $query_salary);

        if ($insert_salary) {
            // Set notifikasi berhasil
            $_SESSION['success_message'] = "Data karyawan berhasil ditambahkan";
        } else {
            // Set notifikasi gagal
            $_SESSION['error_message'] = "Gagal menambahkan data gaji karyawan";
        }
    } else {
        // Set notifikasi gagal
        $_SESSION['error_message'] = "Gagal menambahkan data karyawan";
    }

    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://cdn-icons-png.freepik.com/256/9166/9166850.png?semt=ais_hybrid' rel='shortcut icon'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/add_employee.css" type="text/css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Employee</h2>
        <form method="POST" action="add_employee.php" class="mt-3" id="add_employee_form">
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" placeholder="Enter NIP" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Enter Alamat" required>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>
            <div class="form-group">
                <label for="position">Position</label>
                <select class="form-control" id="position" name="position" required>
                    <option value="" disabled selected>Select Position</option>
                    <option value="Manager">Manager</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>
            <div class="form-group">
                <label for="basic_salary">Basic Salary</label>
                <input type="text" class="form-control" id="basic_salary" name="basic_salary" placeholder="Basic Salary"
                    required readonly>
            </div>
            <div class="form-group">
                <label for="tanggal_masuk">Tanggal Masuk Kerja</label>
                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="submitForm()">Add Employee</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- LINK to JS -->
    <script src="../js/add_employee.js"></script>
</body>

</html>