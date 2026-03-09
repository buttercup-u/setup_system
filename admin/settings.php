<?php 
include 'auth.php'; 
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_user = mysqli_real_escape_string($conn, $_POST['username']);
    $new_pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        $message = "<div class='alert alert-danger border-dark rounded-0'>Passwords do not match!</div>";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE admins SET username = ?, password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $new_user, $hashed_pass, $_SESSION['admin_id']);
        if (mysqli_stmt_execute($stmt)) {
            $message = "<div class='alert alert-success border-dark rounded-0'>System Credentials Updated!</div>";
            $_SESSION['username'] = $new_user;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | SETUP_SYSTEM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --blue: #0056b3; --black: #1a1a1a; }
        body { background: #f4f7f6; padding: 40px; font-family: 'Inter', sans-serif; }
        .settings-card { background: white; border: 2.5px solid var(--black); box-shadow: 8px 8px 0px var(--blue); max-width: 500px; margin: auto; padding: 35px; }
        .form-control { border: 2px solid var(--black); border-radius: 0; }
        .btn-update { background: var(--blue); color: white; border: 2px solid var(--black); border-radius: 0; font-weight: bold; width: 100%; }
    </style>
</head>
<body>
    <div class="settings-card">
        <h4 class="fw-black mb-4 text-uppercase">Account Settings</h4>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="small fw-bold">ADMIN USERNAME</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="small fw-bold">NEW PASSWORD</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="small fw-bold">CONFIRM NEW PASSWORD</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-update py-2">SAVE CHANGES</button>
            <div class="text-center mt-3"><a href="index.php" class="text-dark small fw-bold">Back to Dashboard</a></div>
        </form>
    </div>
</body>
</html>