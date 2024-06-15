<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Hitung jumlah entri per halaman
$entries_per_page = 10;

// Dapatkan halaman saat ini dari URL
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Pastikan nilai halaman tidak kurang dari 1
$current_page = max(1, $current_page);

// Hitung offset berdasarkan halaman saat ini
$offset = ($current_page - 1) * $entries_per_page;

// Query untuk mendapatkan data karyawan dengan batasan dan offset
$query = "SELECT e.name, e.position, e.basic_salary, s.total_salary, e.employee_id
          FROM employee e
          LEFT JOIN salary s ON e.employee_id = s.employee_id
          LIMIT $entries_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

// Hitung jumlah total entri
$total_entries_query = "SELECT COUNT(*) as total FROM employee";
$total_entries_result = mysqli_query($conn, $total_entries_query);
$total_entries_row = mysqli_fetch_assoc($total_entries_result);
$total_entries = $total_entries_row['total'];

// Hitung jumlah total halaman
$total_pages = ceil($total_entries / $entries_per_page);

// Query untuk mendapatkan total gaji untuk setiap posisi
$total_salary_query = "
    SELECT position, SUM(s.total_salary) as total_salary
    FROM employee e
    LEFT JOIN salary s ON e.employee_id = s.employee_id
    GROUP BY position";
$total_salary_result = mysqli_query($conn, $total_salary_query);

// Memproses hasil query ke dalam array
$total_salaries = array();
while ($row = mysqli_fetch_assoc($total_salary_result)) {
    $total_salaries[] = $row;
}

// Cek apakah ada pesan notifikasi yang disimpan dalam session
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Hapus pesan notifikasi setelah ditampilkan

    header("Refresh: 3; url=dashboard.php");

} elseif (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Hapus pesan notifikasi setelah ditampilkan

    header("Refresh: 3; url=dashboard.php");

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://cdn-icons-png.freepik.com/256/9166/9166850.png?semt=ais_hybrid' rel='shortcut icon'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="mb-3">
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link active" href="#">Dashboard</a>
            </li>
          <li class="nav-item">
    <a class="nav-link active" href="all_salary_employee.php">Seluruh Gaji Karyawan</a>
</li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>

            </li>
        </ul>
    </nav>
    <!-- Header -->
    <header class="mt-4 mb-5 text-center">
        <h2 class="company-name">PT. Barokah tbk</h2>
        <h1 class="display-4">Employee Management Dashboard</h1>
        <p class="lead">Efficiently manage your employee data and track salary distribution.</p>
    </header>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- Total Salary Section -->
        <section id="total-salary" class="section-body">
            <h2 class="section-title">Total Salary</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Chart Canvas -->
                    <canvas id="total-salary-chart"></canvas>
                </div>
            </div>
        </section>
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
        <!-- Employee Data Section -->
        <section id="employee-data" class="section-body">
            <h2 class="section-title">Employee Data</h2>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search employee...">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">Search</button>
                </div>
            </div>

                     <nav class="mb-3">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="add_employee.php" id="addEmployeeLink">Add Employee</a>
                    </li>
                </ul>
            </nav>

            <table class="table">
                <!-- Table Header -->
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody>
                    <?php while ($employee = mysqli_fetch_assoc($result)): ?>
                                                                                                                                    <tr>
                                                                                                                                        <td><?php echo $employee['name']; ?></td>
                                                                                                                                        <td><?php echo $employee['position']; ?></td>
                                                                                                                                        <td class='text-center actions'>
                                                                                                                                            <a href='edit_employee.php?id=<?php echo $employee['employee_id']; ?>'
                                                                                                                                                class='btn btn-sm btn-info' id="editEmployeeLink">Edit</a>
                                                                                                                                            <a href='slip_gaji.php?id=<?php echo $employee['employee_id']; ?>'
                                                                                                                                                class='btn btn-sm btn-success'>Slip Gaji</a>
                                                                                                                                            <a class='btn btn-sm btn-danger' href="#" data-toggle="modal"
                                                                                                                                                data-target="#deleteModal<?php echo $employee['employee_id']; ?>">Delete</a>
                                                                                                                                            <!-- Delete Modal -->
                                                                                                                                            <div class="modal fade" id="deleteModal<?php echo $employee['employee_id']; ?>"
                                                                                                                                                tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                                                                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                                                                                    <div class="modal-content">
                                                                                                                                                        <div class="modal-header">
                                                                                                                                                            <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                                                                                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                                                                            </button>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="modal-body">
                                                                                                                                                            Are you sure you want to delete?
                                                                                                                                                        </div>
                                                                                                                                                        <div class="modal-footer">
                                                                                                                                                            <button type="button" class="btn btn-secondary"
                                                                                                                                                                data-dismiss="modal">Cancel</button>
                                                                                                                                                            <a href='delete_employee.php?id=<?php echo $employee['employee_id']; ?>'
                                                                                                                                                                class="btn btn-primary">Delete</a>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
    </div>
    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($current_page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" tabindex="-1"
                    aria-disabled="true">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                                                                                            <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                                                                                                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                                                                            </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($current_page == $total_pages || $total_pages == 0) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
            </li>
        </ul>
    </nav>
    </section>
    <section id="company_data" class="footer">
        <div>
            <h4 class="company_title">PT. Barokah tbk</h4>
            <p class="company_address">Pd. Jaya, Kec. Cipayung, Kota Depok, Jawa Barat 16438</p>
            <p class="company_phone">Phone +62 21 0210020938</p>
        </div>
    </section>
    <!-- Menyimpan data JSON di dalam elemen tersembunyi -->
    <div id="totalSalaryData"
        data-total-salaries='<?php echo json_encode($total_salaries, JSON_HEX_TAG | JSON_HEX_AMP); ?>'></div>
    <!-- Link to JS -->
    <script src="../js/dashboard.js"></script>
</body>

</html>