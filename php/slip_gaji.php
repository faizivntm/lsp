<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';
$id = $_GET['id'];

$query = "SELECT e.*, s.* FROM employee e
        INNER JOIN salary s ON e.employee_id = s.employee_id
        WHERE e.employee_id=$id";
$result = mysqli_query($conn, $query);
$employee = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href='https://cdn-icons-png.freepik.com/256/9166/9166850.png?semt=ais_hybrid' rel='shortcut icon'>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Slip Gaji</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/slip_gaji.css" type="text/css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        @media print{
            .btn-back{
    display:none;
            }

            .ttd_hrd{
    display: block;
            }
        }

                @media screen{
            .ttd_hrd{
    display:none;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <table class="table-responsive">
            <tbody>
                <tr>
                    <th>
                        <img src="https://cdn-icons-png.freepik.com/256/9166/9166850.png?semt=ais_hybrid"
                            alt="logo company" width="100" height="100">
                    </th>
                    <td>
                        <h4 class="company_title">PT. Barokah tbk</h4>
                        <p class="company_address">Pd. Jaya, Kec. Cipayung, Kota Depok, Jawa Barat 16438</p>
                        <p class="company_phone">Phone +62 21 0210020938</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2 class="text-center">Slip Gaji Karyawan</h2>

        <h5>Employee Data</h5>
        <div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>NIP</th>
                        <td><?php echo $employee['NIP']; ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?php echo $employee['name']; ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?php echo $employee['position']; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo $employee['Alamat']; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td><?php
                        $tanggal_lahir = $employee['Tanggal_Lahir'];
                        echo date("d F Y", strtotime($tanggal_lahir));
                        ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk Kerja</th>
                        <td><?php
                        $tanggal_masuk = $employee['Tanggal_Masuk_Kerja'];
                        echo date("d F Y", strtotime($tanggal_lahir));
                        ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
        <h5>Pendapatan</h5>
        <div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Gaji Pokok</th>
                        <td>Rp <?php echo number_format($employee['basic_salary'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Bonus</th>
                        <td>Rp <?php echo number_format($employee['bonus_salary'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Total Pendapatan</th>
                        <td>Rp
                            <?php
                            // Ensure the values exist before summing them up
                            $bonus_salary = isset($employee['bonus_salary']) ? $employee['bonus_salary'] : 0;
                            $basic_salary = isset($employee['basic_salary']) ? $employee['basic_salary'] : 0;

                            echo number_format($bonus_salary + $basic_salary, 0, ',', '.');
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h5>Potongan</h5>
        <div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>PPH 5%</th>
                        <td>Rp <?php echo number_format($employee['pph'], 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h5>Gaji Diterima</h5>
        <div>
            <table class="table table-bordered">
                <tbody>

                    <tr>
                        <th>Gaji Diterima</th>
                        <td>Rp <?php echo number_format($employee['total_salary'], 0, ',', '.'); ?></td>
                    </tr>
            </table>
        </div>
            <div class="text-right mt-4">
            <a id="print-button" class="btn-back">Cetak Slip Gaji</a>
        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
        </div>
        <div class="ttd_hrd">
            <h5 class="company_ttd">Mengetahui</h5>
            <h5 class="company_ttd">Manager HRD</h5>
        </div>
    </div>
    <section class="salary-info-section my-4">
        <div class="container">
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">Salary Calculation Information</h4>
                <p>Employee salaries are calculated based on the base salary, bonus, and a 5% income tax deduction.
                    Bonuses are determined according to the employee's position, with the following provisions:</p>
                <ul>
                    <li>Manager Position: 50% of the base salary</li>
                    <li>Supervisor: 40% of the base salary</li>
                    <li>Staff: 30% of the base salary</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
     <script>
        document.getElementById('print-button').addEventListener('click', function () {
    window.print(); // Mencetak halaman saat tombol dikliks
});
    </script>
</body>

</html>