<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and Sanitize Basic Data
    $comp_id = !empty($_POST['company_id']) ? mysqli_real_escape_string($conn, $_POST['company_id']) : null;
    $date = $_POST['reg_date'];
    $mode = $_POST['mode'];

    // 2. SERVER-SIDE VALIDATION: Check if essential fields are missing
    if (empty($comp_id) || empty($mode)) {
        echo "<script>alert('Error: Cooperator and Payment Mode are required!'); window.history.back();</script>";
        exit;
    }

    // 3. Handle Conditional Data (Amount vs Check Details)
    $amt = !empty($_POST['amount']) ? $_POST['amount'] : "NULL";
    $chk = !empty($_POST['check_no']) ? "'" . mysqli_real_escape_string($conn, $_POST['check_no']) . "'" : "NULL";
    $bnk = !empty($_POST['bank_name']) ? "'" . mysqli_real_escape_string($conn, $_POST['bank_name']) . "'" : "NULL";

    // 4. Extra Guard: Ensure Cash has an amount and Check has details
    if ($mode == "Cash" && $amt == "NULL") {
        echo "<script>alert('Error: Cash amount is missing!'); window.history.back();</script>";
        exit;
    }
    if ($mode == "Check" && ($chk == "NULL" || $bnk == "NULL")) {
        echo "<script>alert('Error: Check details are incomplete!'); window.history.back();</script>";
        exit;
    }

    // 5. Insert into Database
    $sql = "INSERT INTO payments (company_id, registration_date, payment_mode, amount, check_no, bank_name) 
            VALUES ($comp_id, '$date', '$mode', $amt, $chk, $bnk)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Successfully Registered!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>