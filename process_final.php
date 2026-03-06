<<<<<<< HEAD
<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comp_id = $_POST['company_id'];
    $date = $_POST['reg_date'];
    $mode = $_POST['mode'];
    $amt = !empty($_POST['amount']) ? $_POST['amount'] : "NULL";
    $chk = !empty($_POST['check_no']) ? "'" . mysqli_real_escape_string($conn, $_POST['check_no']) . "'" : "NULL";
    $bnk = !empty($_POST['bank_name']) ? "'" . mysqli_real_escape_string($conn, $_POST['bank_name']) . "'" : "NULL";

    $sql = "INSERT INTO payments (company_id, registration_date, payment_mode, amount, check_no, bank_name) 
            VALUES ($comp_id, '$date', '$mode', $amt, $chk, $bnk)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Successfully Registered!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
=======
<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comp_id = $_POST['company_id'];
    $date = $_POST['reg_date'];
    $mode = $_POST['mode'];
    $amt = !empty($_POST['amount']) ? $_POST['amount'] : "NULL";
    $chk = !empty($_POST['check_no']) ? "'" . mysqli_real_escape_string($conn, $_POST['check_no']) . "'" : "NULL";
    $bnk = !empty($_POST['bank_name']) ? "'" . mysqli_real_escape_string($conn, $_POST['bank_name']) . "'" : "NULL";

    $sql = "INSERT INTO payments (company_id, registration_date, payment_mode, amount, check_no, bank_name) 
            VALUES ($comp_id, '$date', '$mode', $amt, $chk, $bnk)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Successfully Registered!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
>>>>>>> 42f08ab9f1ba9fd8a2b755f4c7f6d241c04ce8d3
?>