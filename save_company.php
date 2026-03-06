<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name         = mysqli_real_escape_string($conn, $_POST['cooperator_name']);
    $prop         = mysqli_real_escape_string($conn, $_POST['proprietor']);
    $province     = mysqli_real_escape_string($conn, $_POST['province']);
    $municipality = mysqli_real_escape_string($conn, $_POST['municipality']);
    $barangay     = mysqli_real_escape_string($conn, $_POST['barangay']);
    $contact      = mysqli_real_escape_string($conn, $_POST['contact_no']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $title        = mysqli_real_escape_string($conn, $_POST['project_title']);
    $cost         = $_POST['project_cost'];
    $action       = $_POST['action_type'];

    $sql = "INSERT INTO companies (cooperator_name, proprietor, province, municipality, barangay, contact_no, email, project_title, project_cost) 
            VALUES ('$name', '$prop', '$province', '$municipality', '$barangay', '$contact', '$email', '$title', '$cost')";

    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn);
        
        if ($action === 'payment') {
            header("Location: payment.php?id=" . $last_id);
        } else {
            echo "<script>alert('Registration Successful!'); window.location.href='index.php';</script>";
        }
        exit();
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
}
?>