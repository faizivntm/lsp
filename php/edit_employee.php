<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';
$id = $_GET['id'];

$query = "SELECT * FROM employee WHERE employee_id=$id";
$result = mysqli_query($conn, $query);
$employee = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $basic_salary = $_POST['basic_salary'];
    $nip = $_POST['NIP'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['Tanggal_Lahir'];
    $tanggal_masuk = $_POST['Tanggal_Masuk_Kerja'];

    $basic_salary = (int) str_replace(array(',', '.'), '', $basic_salary);

    $query = "UPDATE employee SET name='$name', position='$position', basic_salary=$basic_salary, NIP='$nip', Alamat='$alamat', Tanggal_Lahir='$tanggal_lahir', Tanggal_Masuk_Kerja='$tanggal_masuk' WHERE employee_id=$id";
    $update_result = mysqli_query($conn, $query);

    if ($update_result) {
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

        // Update data gaji ke dalam tabel salary
        $query_salary = "UPDATE salary SET salary_date='$salary_date', bonus_salary=$bonus, pph=$pph, total_salary=$total_salary WHERE employee_id=$id";
        $update_salary = mysqli_query($conn, $query_salary);

        if ($update_salary) {
            $_SESSION['success_message'] = "Data karyawan berhasil diperbarui";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui data gaji karyawan";
        }
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui data karyawan";
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
    <title>Edit Employee</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/edit_employee.css" type="text/css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-3">Edit Employee</h2>
        <form method="POST" action="edit_employee.php?id=<?php echo $id; ?>" class="mt-3" id="edit_employee_form">
            <div class="form-group">
                <label for="NIP">NIP</label>
                <input type="text" class="form-control" id="NIP" name="NIP" placeholder="Enter NIP" value="<?php echo $employee['NIP']; ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $employee['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $employee['Alamat']; ?>" required>
            </div>
            <div class="form-group">
                <label for="Tanggal_Lahir">Tanggal Lahir</label>
                <input type="date" class="form-control" id="Tanggal_Lahir" name="Tanggal_Lahir" value="<?php echo $employee['Tanggal_Lahir']; ?>" required>
            </div>
            <div class="form-group">
                <label for="position">Position</label>
                <select class="form-control" id="position" name="position" required>
                    <option value="Manager" <?php echo ($employee['position'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                    <option value="Supervisor" <?php echo ($employee['position'] == 'Supervisor') ? 'selected' : ''; ?>>Supervisor</option>
                    <option value="Staff" <?php echo ($employee['position'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                </select>
            </div>
            <div class="form-group">
                <label for="basic_salary">Basic Salary</label>
                <input type="text" class="form-control" id="basic_salary" name="basic_salary" value="<?php echo number_format($employee['basic_salary'], 0, ',', '.'); ?>" required>
            </div>
            <div class="form-group">
                <label for="Tanggal_Masuk_Kerja">Tanggal Masuk Kerja</label>
                <input type="date" class="form-control" id="Tanggal_Masuk_Kerja" name="Tanggal_Masuk_Kerja" value="<?php echo $employee['Tanggal_Masuk_Kerja']; ?>" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Edit Employee</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script untuk format rupiah -->
    <script src="../js/edit_employee.js"></script>
    <script>
        $(document).ready(function () {
            // Set initial basic salary based on current position
            let position = $('#position').val();
            setBasicSalary(position);

            // Update basic salary when position changes
            $('#position').change(function () {
                setBasicSalary(this.value);
            });

            function setBasicSalary(position) {
                if (position == "Manager") {
                    basicSalary = 1500000;
                } else if (position == "Supervisor") {
                    basicSalary = 1000000;
                } else if (position == "Staff") {
                    basicSalary = 500000;
                } else {
                    basicSalary = "<?php echo $employee['basic_salary']; ?>";
                }
                $('#basic_salary').val(basicSalary.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
            }
        });
    </script>
</body>

</html>
