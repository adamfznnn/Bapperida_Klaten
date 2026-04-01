<?php
ob_start();
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'koneksi.php';

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['login_time'] = time();

        // Cek apakah kolom user_registration_id ada
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'user_registration_id'");

        if ($check_column && mysqli_num_rows($check_column) > 0) {
            // Kolom sudah ada
            if (isset($user['user_registration_id']) && !empty($user['user_registration_id'])) {
                $_SESSION['registration_id'] = $user['user_registration_id'];
            } else {
                // Generate ID baru jika belum ada
                $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE user_registration_id IS NOT NULL AND user_registration_id != ''");
                $count_row = mysqli_fetch_assoc($count_result);
                $next_id = ($count_row['total'] ?? 0) + 1;
                $reg_id = str_pad($next_id, 4, '0', STR_PAD_LEFT);

                // Update ke database
                mysqli_query($conn, "UPDATE users SET user_registration_id='$reg_id' WHERE id_user='" . intval($user['id_user']) . "'");
                $_SESSION['registration_id'] = $reg_id;
            }
        } else {
            // Fallback jika kolom belum ada
            $_SESSION['registration_id'] = 'USER-' . intval($user['id_user']);
        }

        mysqli_close($conn);
        ob_clean();
        header('Location: index.php', true, 302);
        exit();
    } else {
        $error = "Username atau password salah!";
        if ($conn)
            mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .login-body {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group input {
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .demo-info {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .demo-info h6 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #667eea;
        }

        .demo-info p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-container">
                    <div class="login-header">
                        <h1><i class="fas fa-file-alt me-2"></i>SIJADI</h1>
                        <p style="margin-top: 10px; font-size: 14px;">Sistem Informasi SPPD</p>
                    </div>
                    <div class="login-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required
                                    placeholder="Masukkan username">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required
                                    placeholder="Masukkan password">
                            </div>

                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>