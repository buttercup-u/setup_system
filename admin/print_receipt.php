<?php
session_start();
include '../db.php'; // Ensure correct path to your DB connection

if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    die("Unauthorized access or missing Transaction ID.");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch specific payment details joined with company info
$sql = "SELECT p.*, c.cooperator_name, c.municipality 
        FROM payments p 
        JOIN companies c ON p.company_id = c.id 
        WHERE p.id = '$id'";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);

if (!$data) { die("Transaction not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt_<?php echo $data['id']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .receipt-box { 
            max-width: 550px; 
            margin: 50px auto; 
            background: #fff; 
            padding: 40px; 
            border: 1px solid #ddd;
            position: relative;
        }
        .header-section { border-bottom: 3px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .label { font-size: 0.7rem; font-weight: 800; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .value { font-size: 1.1rem; font-weight: 600; color: #000; margin-bottom: 20px; border-bottom: 1px dashed #eee; padding-bottom: 5px; }
        .amount-highlight { background: #000; color: #fff; padding: 15px; text-align: center; margin-top: 20px; }
        .footer-sig { margin-top: 60px; border-top: 1px solid #000; display: inline-block; width: 200px; text-align: center; }
        
        @media print {
            body { background: #fff; }
            .receipt-box { border: 1px solid #000; margin: 0; width: 100%; max-width: 100%; box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print text-center my-4">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">PRINT NOW</button>
        <button onclick="window.close()" class="btn btn-outline-secondary rounded-pill px-4">CLOSE</button>
    </div>

    <div class="receipt-box">
        <div class="header-section d-flex justify-content-between align-items-end">
            <div>
                <img src="../setup.png" height="45" class="mb-2">
                <h5 class="fw-bold mb-0">PAYMENT ACKNOWLEDGMENT</h5>
            </div>
            <div class="text-end">
                <p class="label mb-0">Receipt No.</p>
                <h5 class="fw-bold">OR-<?php echo str_pad($data['id'], 5, '0', STR_PAD_LEFT); ?></h5>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <p class="label mb-1">Payor / Cooperator</p>
                <p class="value text-uppercase"><?php echo htmlspecialchars($data['cooperator_name']); ?></p>
            </div>
            <div class="col-6">
                <p class="label mb-1">Date Processed</p>
                <p class="value"><?php echo date('F d, Y', strtotime($data['registration_date'])); ?></p>
            </div>
            <div class="col-6">
                <p class="label mb-1">Payment Mode</p>
                <p class="value text-uppercase"><?php echo htmlspecialchars($data['payment_mode']); ?></p>
            </div>
            <?php if($data['payment_mode'] == 'Check'): ?>
            <div class="col-12">
                <p class="label mb-1">Check Details (Bank / No.)</p>
                <p class="value"><?php echo htmlspecialchars($data['bank_name'] . ' - ' . $data['check_no']); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="amount-highlight">
            <p class="label text-white-50 mb-0">Total Amount Received</p>
            <h2 class="fw-bold mb-0">₱ <?php echo number_format($data['amount'], 2); ?></h2>
        </div>

        <div class="d-flex justify-content-between mt-5">
            <div>
                <p class="small text-muted mb-0">System Generated</p>
                <p class="small fw-bold"><?php echo date('M d, Y | h:i A'); ?></p>
            </div>
            <div class="text-center">
                <div class="footer-sig"></div>
                <p class="label mt-1">Authorized Signature</p>
                <p class="small fw-bold text-uppercase"><?php echo $_SESSION['username'] ?? 'Admin'; ?></p>
            </div>
        </div>
    </div>

</body>
</html>