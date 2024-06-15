<?php
session_start();
$configs = include ('config.php');

$error = '';  // Initialize error variable


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengambil data admin berdasarkan username
    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    // Memeriksa apakah username dan password sesuai (tanpa hashing)
    if ($admin && $password === $admin['password']) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href='https://cdn-icons-png.freepik.com/256/9166/9166850.png?semt=ais_hybrid' rel='shortcut icon'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css" type="text/css">

</head>

<body>
    <!-- Header -->
    <header class="mt-4 mb-5 text-center">
        <h2 class="company-name">PT. Barokah tbk</h2>
        <h1 class="display-4">Employee Management System</h1>
        <p class="lead">Efficiently manage your employee data and track salary distribution.</p>
    </header>
    <div class="container">

        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Login</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form id="loginForm" method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"
                                    id="togglePassword">Show</button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- JavaScript -->
    <script src="../js/login.js">
    </script>
</body>

</html>