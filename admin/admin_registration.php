<?php include 'header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_user = mysqli_real_escape_string($conn, $_POST['username']);
    
    $check = mysqli_query($conn, "SELECT id FROM admins WHERE username = '$new_user'");
    if(mysqli_num_rows($check) > 0) {
        $msg = "<div class='alert alert-danger'>Username already exists!</div>";
    } else {
        mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$new_user', 'temporary')");
        $msg = "<div class='alert alert-success'>Admin '$new_user' created successfully!</div>";
    }
}
?>

<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold">Register New Admin</h1>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4" style="border-left: 5px solid var(--brand-blue) !important;">
            <?php if(isset($msg)) echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="small fw-bold text-uppercase">Desired Username</label>
                    <input type="text" name="username" class="form-control border-2" placeholder="e.g. staff_01" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-uppercase">Initial Password</label>
                    <input type="text" class="form-control border-2 bg-light" value="temporary" readonly>
                    <p class="small text-muted mt-1">Passwords are currently in bypass mode for setup.</p>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 border-2 text-uppercase">
                    Create Admin Account
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>