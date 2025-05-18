<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Ekskul Al Amanah</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
        }

        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            color: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .glass-card h2 {
            font-weight: 700;
            margin-bottom: 30px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            height: 45px;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: #fff;
        }

        .form-control-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #555;
        }

        .position-relative input {
            padding-left: 40px;
        }

        .btn-primary {
            background-color: #667eea;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
        }

        a {
            color: #fff;
            opacity: 0.8;
            text-decoration: none;
        }

        a:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="glass-card">
        <h2 class="text-center">Login</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="login-act.php" method="post">
            <div class="form-group position-relative mb-3">
                <span class="form-control-icon"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>  
            <div class="form-group position-relative mb-4">
                <span class="form-control-icon"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block w-100 mb-3">Login</button>
            <div class="text-center">
                <small><a href="register.php">Belum punya akun? Daftar</a> </small>
            </div>
        </form>
    </div>
</body>
</html>
