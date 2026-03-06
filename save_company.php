<<<<<<< HEAD
<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action_type'];

    // Check if the user clicked payment but left the form empty
    if ($action === 'payment' && empty($_POST['cooperator_name'])) {
        header("Location: payment.php");
        exit();
    }

    // Sanitize data for registration
    $name    = mysqli_real_escape_string($conn, $_POST['cooperator_name']);
    $prop    = mysqli_real_escape_string($conn, $_POST['proprietor']);
    $prov    = mysqli_real_escape_string($conn, $_POST['province']);
    $muni    = mysqli_real_escape_string($conn, $_POST['municipality']);
    $brgy    = mysqli_real_escape_string($conn, $_POST['barangay']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact_no']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $title   = mysqli_real_escape_string($conn, $_POST['project_title']);
    $cost    = $_POST['project_cost'];

    $sql = "INSERT INTO companies (cooperator_name, proprietor, province, municipality, barangay, contact_no, email, project_title, project_cost) 
            VALUES ('$name', '$prop', '$prov', '$muni', '$brgy', '$contact', '$email', '$title', '$cost')";

    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn);
        if ($action === 'payment') {
            header("Location: payment.php?id=" . $last_id);
        } else {
            echo "<script>alert('Registration Successful!'); window.location.href='index.php';</script>";
        }
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
=======
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
>>>>>>> 42f08ab9f1ba9fd8a2b755f4c7f6d241c04ce8d3
?>