<?php
// Database name updated to "setup"
$conn = mysqli_connect("localhost", "root", "", "setup");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>