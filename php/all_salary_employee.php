<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';

$query = "SELECT * FROM employee";

$query = "SELECT e.*, s.* FROM employee e
        INNER JOIN salary s ON e.employee_id = s.employee_id";
$result = mysqli_query($conn, $query);
$result = mysqli_query($conn, $query);
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
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Employee List</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Employee ID</th>
                                        <th scope="col">Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Basic Salary</th>
                    <th scope="col">Total Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($employee = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $employee['employee_id']; ?></td>
                                                                                    <td><?php echo $employee['name']; ?></td>

                                            <td><?php echo $employee['name']; ?></td>
                                            <td><?php echo $employee['position']; ?></td>
                                            <td><?php echo $employee['basic_salary']; ?></td>
                                            <td><?php echo $employee['total_salary']; ?></td>
                                        </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
