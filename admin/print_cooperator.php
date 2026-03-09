<?php
session_start();
include '../db.php';

// Security: Ensure only logged-in admins can print
if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM companies WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) { die("Record not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>S.E.T.U.P. Official Record - <?php echo $data['id']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @page { size: portrait; margin: 0; }
        body { font-family: 'Inter', sans-serif; background: #fff; color: #333; }
        .print-container { width: 800px; margin: auto; padding: 40px; }
        
        /* Header Style */
        .official-header { border-bottom: 4px solid #1a1a1a; padding-bottom: 20px; margin-bottom: 40px; }
        .system-logo { height: 60px; filter: grayscale(100%); }
        
        /* Data Grid */
        .info-label { font-size: 0.75rem; font-weight: 800; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .info-value { font-size: 1.1rem; font-weight: 600; color: #000; padding-bottom: 15px; margin-bottom: 15px; border-bottom: 1px dashed #eee; }
        
        /* Status Badge for Print */
        .print-badge { border: 2px solid #000; padding: 5px 15px; font-weight: 800; display: inline-block; border-radius: 5px; }

        @media print {
            .no-print { display: none; }
            .print-container { width: 100%; padding: 20px; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print bg-dark p-3 text-center">
        <button onclick="window.print()" class="btn btn-primary fw-bold">PRINT DOCUMENT</button>
        <button onclick="window.close()" class="btn btn-outline-light ms-2">CLOSE PREVIEW</button>
    </div>

    <div class="print-container">
        <div class="official-header d-flex justify-content-between align-items-end">
            <div>
                <img src="../setup.png" class="system-logo mb-2">
                <h4 class="fw-bold mb-0">S.E.T.U.P. SYSTEM</h4>
                <p class="text-muted small mb-0">Database Management & Administrative Records</p>
            </div>
            <div class="text-end">
                <div class="print-badge mb-2">OFFICIAL RECORD</div>
                <p class="small text-muted mb-0">Internal Ref: #<?php echo str_pad($data['id'], 6, '0', STR_PAD_LEFT); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <h5 class="fw-bold text-decoration-underline">COOPERATOR PROFILE</h5>
            </div>
            
            <div class="col-7">
                <div class="info-label">Organization / Full Name</div>
                <div class="info-value text-uppercase"><?php echo htmlspecialchars($data['cooperator_name']); ?></div>
                
                <div class="info-label">Account Creation Date</div>
                <div class="info-value">Generated via SETUP_system</div>
            </div>

            <div class="col-5 border-start ps-4">
                <div class="info-label">Registration Status</div>
                <div class="info-value text-success">VERIFIED ACTIVE</div>

                <div class="info-label">Location Base</div>
                <div class="info-value">Koronadal City, South Cotabato</div>
            </div>
        </div>

        <div class="mt-5 pt-5 row align-items-center">
            <div class="col-8">
                <p class="small text-muted">
                    <strong>Certification Notice:</strong> This document is an automated output of the S.E.T.U.P. administrative portal. 
                    Any unauthorized alteration of this printed data is strictly prohibited by system security protocols.
                </p>
                <p class="small text-muted mt-3">
                    Printed by: <strong><?php echo strtoupper($_SESSION['username']); ?></strong><br>
                    Date: <?php echo date('M d, Y | h:i A'); ?>
                </p>
            </div>
            <div class="col-4 text-center">
                <div style="border-top: 2px solid #000; padding-top: 10px;" class="fw-bold small text-uppercase">
                    Authorized Administrator
                </div>
            </div>
        </div>
    </div>

</body>
</html>