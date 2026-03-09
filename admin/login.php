<?php
session_start();
include '../db.php'; 

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Internal Check: Only checking if username exists in the admins table
    $stmt = mysqli_prepare($conn, "SELECT id FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['username'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error = "Access Denied: Username '$user' not recognized.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | S.E.T.U.P.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/vendors.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        :root { --brand-blue: #0061f2; --brand-dark: #1a1a1a; }
        body { 
            background: radial-gradient(circle at top right, #2c2c2c, #000000); 
            font-family: 'Inter', sans-serif;
            display: flex; align-items: center; justify-content: center; 
            height: 100vh; margin: 0; overflow: hidden;
        }
        .login-card { 
            background: rgba(255, 255, 255, 0.98); padding: 50px 40px; 
            border-radius: 24px; width: 100%; max-width: 420px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); position: relative;
        }
        .login-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 8px;
            background: linear-gradient(90deg, var(--brand-blue), #6900f2);
            border-radius: 24px 24px 0 0;
        }
        .custom-input {
            background-color: #f3f6f9; border: 2px solid #f3f6f9;
            border-radius: 12px; padding: 12px 15px; transition: all 0.3s; font-weight: 500;
        }
        .custom-input:focus {
            background-color: #fff; border-color: var(--brand-blue);
            box-shadow: 0 0 0 4px rgba(0, 97, 242, 0.1); outline: none;
        }
        .btn-login { 
            background: var(--brand-dark); color: white; font-weight: 800; 
            padding: 14px; width: 100%; border: none; border-radius: 12px; 
            transition: 0.3s; letter-spacing: 1px; margin-top: 10px;
        }
        .btn-login:hover { background: #000; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2); }
        .divider { 
            display: flex; align-items: center; text-align: center; margin: 30px 0; 
            color: #aaa; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
        }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #eee; }
        .divider:not(:empty)::before { margin-right: 1.5em; }
        .divider:not(:empty)::after { margin-left: 1.5em; }
        .error-shake { animation: shake 0.4s; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>

    <div class="login-card <?php echo !empty($error) ? 'error-shake' : ''; ?>">
        <div class="text-center mb-5">
            <img src="../setup.png" alt="Logo" style="height: 55px; filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));">
            <h3 class="fw-800 mt-4 mb-1" style="letter-spacing: -1px; color: #1a1a1a;">Internal Portal</h3>
            <p class="text-muted small fw-bold text-uppercase" style="letter-spacing: 1px;">S.E.T.U.P. Management</p>
        </div>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'logged_out'): ?>
            <div class="alert border-0 small fw-bold mb-4" style="border-radius: 12px; background-color: #f0fff4; color: #2f855a;">
                <i class="bi bi-check-circle-fill me-2"></i> Session ended safely.
            </div>
        <?php endif; ?>

        <?php if(!empty($error)): ?>
            <div class="alert border-0 small fw-bold mb-4" style="border-radius: 12px; background-color: #fff5f5; color: #e53e3e;">
                <i class="bi bi-shield-exclamation me-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="small fw-bold text-muted mb-2 d-block px-1 text-uppercase">Admin Username</label>
                <input type="text" name="username" class="form-control custom-input" placeholder="Enter credentials" required autofocus>
            </div>
            
            <button type="submit" class="btn-login shadow-sm text-uppercase">
                Authenticate <i class="bi bi-fingerprint ms-2"></i>
            </button>
        </form>

        <div class="divider">Security Notice</div>
        
        <div class="text-center">
            <p class="small text-muted mb-0">Unauthorized access to this terminal is monitored and logged.</p>
            <p class="mt-4 text-muted" style="font-size: 0.75rem; font-weight: 500;">
                &copy; 2026 S.E.T.U.P. SYSTEM
            </p>
        </div>
    </div>

</body>
</html>