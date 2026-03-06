<<<<<<< HEAD
<?php 
include 'db.php';

// 1. Capture Filter Inputs
$selected_month = isset($_GET['month']) ? $_GET['month'] : '';
$selected_year = isset($_GET['year']) ? $_GET['year'] : '';
$selected_company = isset($_GET['company_id']) ? $_GET['company_id'] : '';

// 2. Fetch Data for Dropdowns
$companies_query = mysqli_query($conn, "SELECT id, cooperator_name FROM companies ORDER BY cooperator_name ASC");
$years_query = mysqli_query($conn, "SELECT DISTINCT YEAR(registration_date) as year FROM payments WHERE registration_date IS NOT NULL ORDER BY year DESC");

$years = []; 
while ($y = mysqli_fetch_assoc($years_query)) { $years[] = $y['year']; }

// 3. Construct Dynamic Filter Query
$where_conditions = [];
if (!empty($selected_company)) { 
    $where_conditions[] = "p.company_id = '" . mysqli_real_escape_string($conn, $selected_company) . "'"; 
}
if (!empty($selected_month)) { 
    $where_conditions[] = "MONTH(p.registration_date) = '" . mysqli_real_escape_string($conn, $selected_month) . "'"; 
}
if (!empty($selected_year)) { 
    $where_conditions[] = "YEAR(p.registration_date) = '" . mysqli_real_escape_string($conn, $selected_year) . "'"; 
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : '';

// 4. Main Query Execution
$query = "SELECT p.*, c.cooperator_name, c.municipality 
          FROM payments p 
          INNER JOIN companies c ON p.company_id = c.id 
          $where_clause 
          ORDER BY p.registration_date DESC";

$payments = mysqli_query($conn, $query);

$payment_data = []; $total_amount = 0; $cash_total = 0;
while ($row = mysqli_fetch_assoc($payments)) {
    $payment_data[] = $row;
    if ($row['payment_mode'] == 'Cash') { $cash_total += $row['amount']; }
    $total_amount += $row['amount'];
}

$month_names = [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>S.E.T.U.P Collection Report</title>
    <link rel="icon" type="image/png" href="setup.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --brand-blue: #0056b3; --brand-black: #1a1a1a; }
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; padding: 20px; }
        .report-card { border: 2.5px solid var(--brand-black); border-radius: 0; box-shadow: 8px 8px 0px var(--brand-blue); background: white; margin-bottom: 25px; }
        .report-header { padding: 30px; border-bottom: 3px solid var(--brand-blue); display: flex; align-items: center; justify-content: space-between; }
        .report-logo { height: 90px; width: auto; }
        .report-title-group h1 { color: var(--brand-blue); font-weight: 900; letter-spacing: 2px; margin: 0; }
        .summary-box { background: var(--brand-blue); color: white; padding: 25px; border: 2.5px solid var(--brand-black); border-radius: 0; height: 100%; }
        .btn-custom { border-radius: 0; font-weight: 800; text-transform: uppercase; border: 2.5px solid var(--brand-black); transition: 0.2s; }
        .btn-custom:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px rgba(0,0,0,0.1); }
        .form-select { border: 1.5px solid var(--brand-black); border-radius: 0; font-weight: 600; padding: 10px; }
        @media print { .no-print { display: none; } body { padding: 0; } .report-card { box-shadow: none; border: 1px solid #000; } }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="report-card report-header mb-4">
            <div class="d-flex align-items-center">
                <img src="setup.png" alt="Logo" class="report-logo me-4">
                <div class="report-title-group">
                    <h1>COLLECTION REPORT</h1>
                    <p class="m-0 fw-bold text-uppercase text-muted">Official Transaction Log • S.E.T.U.P System</p>
                </div>
            </div>
            <div class="no-print">
                <a href="index.php" class="btn btn-outline-dark btn-custom me-2"><i class="bi bi-house"></i> Home</a>
                <button onclick="window.print()" class="btn btn-dark btn-custom"><i class="bi bi-printer"></i> Print</button>
            </div>
        </div>

        <div class="report-card p-4 no-print">
            <form method="GET" action="report.php" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold mb-1">COMPANY NAME</label>
                    <select name="company_id" class="form-select">
                        <option value="">All Registered Companies</option>
                        <?php mysqli_data_seek($companies_query, 0); while($c = mysqli_fetch_assoc($companies_query)): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($selected_company == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['cooperator_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold mb-1">MONTH</label>
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        <?php foreach($month_names as $num => $name): ?>
                            <option value="<?php echo $num; ?>" <?php echo ($selected_month == $num) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold mb-1">YEAR</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        <?php foreach($years as $y): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($selected_year == $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-custom w-100 py-2" style="background-color: var(--brand-blue); border-color: var(--brand-blue); color: white;">
                        <i class="bi bi-search me-2"></i> FILTER RESULTS
                    </button>
                </div>
            </form>
        </div>

        <div class="row mb-4 g-4">
            <div class="col-md-4">
                <div class="summary-box text-center shadow">
                    <h3>₱<?php echo number_format($total_amount, 2); ?></h3>
                    <p class="m-0 fw-bold">TOTAL COLLECTIONS</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="report-card p-4 h-100 shadow border-success">
                    <h3 class="text-success"><?php echo count($payment_data); ?></h3>
                    <p class="m-0 fw-bold text-muted text-uppercase">Total Transactions</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="report-card p-4 h-100 shadow">
                    <h3>₱<?php echo number_format($cash_total, 2); ?></h3>
                    <p class="m-0 fw-bold text-muted text-uppercase">Cash Total</p>
                </div>
            </div>
        </div>

        <div class="report-card overflow-hidden">
            <table class="table table-hover table-bordered m-0 border-dark">
                <thead class="table-dark text-uppercase small">
                    <tr>
                        <th class="p-3">Date</th>
                        <th class="p-3">Company</th>
                        <th class="p-3">Municipality</th>
                        <th class="p-3">Mode</th>
                        <th class="p-3 text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($payment_data)): ?>
                        <tr><td colspan="5" class="text-center py-5 fw-bold text-muted italic">No data found matching your current filters.</td></tr>
                    <?php else: foreach($payment_data as $p): ?>
                    <tr>
                        <td class="p-3"><?php echo date('M d, Y', strtotime($p['registration_date'])); ?></td>
                        <td class="p-3 fw-bold"><?php echo htmlspecialchars($p['cooperator_name']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($p['municipality']); ?></td>
                        <td class="p-3 text-center">
                            <span class="badge <?php echo $p['payment_mode']=='Cash'?'bg-success':'bg-warning text-dark'; ?> px-3 border border-dark">
                                <?php echo $p['payment_mode']; ?>
                            </span>
                        </td>
                        <td class="p-3 text-end fw-bold">₱<?php echo number_format($p['amount'], 2); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
=======
<?php 
include 'db.php';

// Get filter values from request
$selected_month = isset($_GET['month']) ? $_GET['month'] : '';
$selected_year = isset($_GET['year']) ? $_GET['year'] : '';
$selected_company = isset($_GET['company_id']) ? $_GET['company_id'] : '';

// Fetch all companies for dropdown
$companies_query = mysqli_query($conn, "SELECT id, cooperator_name, proprietor, municipality FROM companies ORDER BY cooperator_name ASC");

// Build the SQL query based on filters
$where_conditions = [];

if (!empty($selected_company)) {
    $where_conditions[] = "p.company_id = '$selected_company'";
}
if (!empty($selected_month)) {
    $where_conditions[] = "MONTH(p.registration_date) = '$selected_month'";
}
if (!empty($selected_year)) {
    $where_conditions[] = "YEAR(p.registration_date) = '$selected_year'";
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

// Main query to fetch payment records with company details
$query = "SELECT 
            p.payment_id,
            p.company_id,
            p.registration_date,
            p.payment_mode,
            p.amount,
            p.check_no,
            p.bank_name,
            c.cooperator_name, 
            c.proprietor,
            c.province,
            c.municipality,
            c.barangay,
            c.contact_no,
            c.email,
            c.project_title,
            c.project_cost
          FROM payments p
          INNER JOIN companies c ON p.company_id = c.id
          $where_clause
          ORDER BY p.registration_date DESC, c.cooperator_name ASC";

$payments = mysqli_query($conn, $query);

// Calculate totals
$total_amount = 0;
$cash_total = 0;
$check_total = 0;
$payment_count = mysqli_num_rows($payments);

// Store payments in array for display and calculations
$payment_data = [];
while ($row = mysqli_fetch_assoc($payments)) {
    $payment_data[] = $row;
    if ($row['payment_mode'] == 'Cash') {
        $cash_total += $row['amount'];
    } else {
        $check_total += $row['amount'];
    }
    $total_amount += $row['amount'];
}

// Get available years for dropdown
$years_query = mysqli_query($conn, "SELECT DISTINCT YEAR(registration_date) as year FROM payments ORDER BY year DESC");
$years = [];
while ($year_row = mysqli_fetch_assoc($years_query)) {
    $years[] = $year_row['year'];
}

// Month names for display
$month_names = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

// Get company details for summary if a specific company is selected
$selected_company_details = null;
if (!empty($selected_company)) {
    $company_detail_query = mysqli_query($conn, "SELECT * FROM companies WHERE id = '$selected_company'");
    $selected_company_details = mysqli_fetch_assoc($company_detail_query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.E.T.U.P Payment Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --brand-blue: #0056b3; --brand-black: #1a1a1a; }
        body { background-color: #f4f7f6; color: var(--brand-black); font-family: 'Inter', sans-serif; padding: 20px; }
        .main-container { max-width: 1400px; margin: 0 auto; }
        .card { border: 1px solid var(--brand-black); border-radius: 0; box-shadow: 12px 12px 0px var(--brand-blue); background: white; margin-bottom: 25px; }
        .card-header { background-color: white; border-bottom: 2px solid var(--brand-black); padding: 20px; }
        .setup-header { color: var(--brand-blue); font-weight: 900; letter-spacing: 4px; margin: 0; font-size: 2rem; }
        .filter-section { background-color: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; margin-bottom: 25px; }
        .form-control, .form-select { border: 1px solid #ced4da; border-radius: 0; padding: 10px; }
        .btn-custom { border-radius: 0; font-weight: 700; font-size: 0.85rem; padding: 10px 20px; text-transform: uppercase; }
        .btn-filter { border: 2px solid var(--brand-blue); background: var(--brand-blue); color: white; }
        .btn-reset { border: 2px solid var(--brand-black); background: transparent; }
        .btn-print { border: 2px solid #28a745; background: #28a745; color: white; }
        .summary-card { background: linear-gradient(135deg, var(--brand-blue) 0%, #003d7a 100%); color: white; padding: 20px; margin-bottom: 25px; }
        .summary-item { text-align: center; padding: 15px; border-right: 1px solid rgba(255,255,255,0.2); }
        .summary-item:last-child { border-right: none; }
        .summary-number { font-size: 2rem; font-weight: 900; line-height: 1.2; }
        .summary-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
        .table { margin-bottom: 0; }
        .table thead th { background-color: var(--brand-black); color: white; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; border: none; }
        .table td { vertical-align: middle; }
        .badge-cash { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 0; }
        .badge-check { background-color: #ffc107; color: var(--brand-black); padding: 5px 10px; border-radius: 0; }
        .no-records { text-align: center; padding: 50px; background: #f8f9fa; }
        .no-records i { font-size: 3rem; color: #dee2e6; margin-bottom: 15px; }
        .action-buttons { display: flex; gap: 10px; justify-content: flex-end; }
        .company-info { background-color: #e9ecef; padding: 15px; margin-bottom: 20px; border-left: 5px solid var(--brand-blue); }
        .info-label { font-weight: bold; color: var(--brand-blue); font-size: 0.85rem; text-transform: uppercase; }
        .full-address { font-size: 0.9rem; color: #6c757d; }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1 class="setup-header">S.E.T.U.P</h1>
                <span class="fs-4 fw-bold">PAYMENT REPORT</span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">MONTH</label>
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        <?php foreach ($month_names as $month_num => $month_name): ?>
                            <option value="<?php echo $month_num; ?>" <?php echo ($selected_month == $month_num) ? 'selected' : ''; ?>>
                                <?php echo $month_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-bold">YEAR</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        <?php 
                        $current_year = date('Y');
                        // Add current year and previous years if there are payments
                        if (!empty($years)) {
                            foreach ($years as $year) {
                                echo "<option value='$year' " . ($selected_year == $year ? 'selected' : '') . ">$year</option>";
                            }
                        } else {
                            for ($year = $current_year; $year >= 2020; $year--): 
                        ?>
                            <option value="<?php echo $year; ?>" <?php echo ($selected_year == $year) ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                        <?php 
                            endfor;
                        } 
                        ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-bold">COMPANY</label>
                    <select name="company_id" class="form-select">
                        <option value="">All Companies</option>
                        <?php 
                        // Reset the pointer for companies query
                        mysqli_data_seek($companies_query, 0);
                        while($company = mysqli_fetch_assoc($companies_query)): 
                        ?>
                            <option value="<?php echo $company['id']; ?>" <?php echo ($selected_company == $company['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($company['cooperator_name']); ?>
                                <?php if (!empty($company['municipality'])): ?>
                                    - <?php echo htmlspecialchars($company['municipality']); ?>
                                <?php endif; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-filter btn-custom">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <a href="report.php" class="btn btn-reset btn-custom">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Company Info (if specific company selected) -->
        <?php if ($selected_company_details): ?>
        <div class="company-info">
            <div class="row">
                <div class="col-md-3">
                    <span class="info-label">Company Name</span>
                    <div><?php echo htmlspecialchars($selected_company_details['cooperator_name']); ?></div>
                </div>
                <div class="col-md-2">
                    <span class="info-label">Proprietor</span>
                    <div><?php echo htmlspecialchars($selected_company_details['proprietor']); ?></div>
                </div>
                <div class="col-md-3">
                    <span class="info-label">Address</span>
                    <div class="full-address">
                        <?php 
                        $address_parts = [];
                        if (!empty($selected_company_details['barangay'])) $address_parts[] = $selected_company_details['barangay'];
                        if (!empty($selected_company_details['municipality'])) $address_parts[] = $selected_company_details['municipality'];
                        if (!empty($selected_company_details['province'])) $address_parts[] = $selected_company_details['province'];
                        echo !empty($address_parts) ? htmlspecialchars(implode(', ', $address_parts)) : 'N/A';
                        ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <span class="info-label">Contact</span>
                    <div><?php echo htmlspecialchars($selected_company_details['contact_no'] ?? 'N/A'); ?></div>
                </div>
                <div class="col-md-2">
                    <span class="info-label">Project Title</span>
                    <div><?php echo htmlspecialchars($selected_company_details['project_title']); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="row summary-card g-0">
            <div class="col-md-3 summary-item">
                <div class="summary-number"><?php echo number_format($payment_count); ?></div>
                <div class="summary-label">Total Transactions</div>
            </div>
            <div class="col-md-3 summary-item">
                <div class="summary-number">₱<?php echo number_format($total_amount, 2); ?></div>
                <div class="summary-label">Total Amount</div>
            </div>
            <div class="col-md-3 summary-item">
                <div class="summary-number">₱<?php echo number_format($cash_total, 2); ?></div>
                <div class="summary-label">Cash Payments</div>
            </div>
            <div class="col-md-3 summary-item">
                <div class="summary-number">₱<?php echo number_format($check_total, 2); ?></div>
                <div class="summary-label">Check Payments</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">Filter Results:</span>
                <?php if (!empty($selected_month)): ?>
                    <span class="badge bg-secondary me-1"><?php echo $month_names[$selected_month]; ?></span>
                <?php endif; ?>
                <?php if (!empty($selected_year)): ?>
                    <span class="badge bg-secondary me-1"><?php echo $selected_year; ?></span>
                <?php endif; ?>
                <?php if (!empty($selected_company) && $selected_company_details): ?>
                    <span class="badge bg-secondary"><?php echo htmlspecialchars($selected_company_details['cooperator_name']); ?></span>
                <?php endif; ?>
                <?php if (empty($selected_month) && empty($selected_year) && empty($selected_company)): ?>
                    <span class="badge bg-secondary">All Records</span>
                <?php endif; ?>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-print btn-custom">
                    <i class="bi bi-printer"></i> Print Report
                </button>
                <a href="payment.php" class="btn btn-filter btn-custom">
                    <i class="bi bi-plus-circle"></i> New Payment
                </a>
            </div>
        </div>

        <!-- Payment Records Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Company</th>
                            <th>Proprietor</th>
                            <th>Municipality</th>
                            <th>Mode</th>
                            <th>Check Details</th>
                            <th class="text-end">Amount (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payment_data)): ?>
                            <?php foreach ($payment_data as $payment): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($payment['registration_date'])); ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($payment['cooperator_name']); ?></td>
                                <td><?php echo htmlspecialchars($payment['proprietor']); ?></td>
                                <td><?php echo htmlspecialchars($payment['municipality'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if ($payment['payment_mode'] == 'Cash'): ?>
                                        <span class="badge-cash">CASH</span>
                                    <?php else: ?>
                                        <span class="badge-check">CHECK</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($payment['payment_mode'] == 'Check'): ?>
                                        <small>Check #: <?php echo htmlspecialchars($payment['check_no'] ?? 'N/A'); ?><br>
                                        Bank: <?php echo htmlspecialchars($payment['bank_name'] ?? 'N/A'); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">---</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end fw-bold">₱<?php echo number_format($payment['amount'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-records">
                                    <i class="bi bi-inbox"></i>
                                    <h5>No Payment Records Found</h5>
                                    <p class="text-muted">Try adjusting your filters or add new payments.</p>
                                    <a href="payment.php" class="btn btn-filter btn-custom mt-3">
                                        <i class="bi bi-plus-circle"></i> Add Payment
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($payment_data)): ?>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="6" class="text-end fw-bold">TOTAL:</td>
                            <td class="text-end fw-bold">₱<?php echo number_format($total_amount, 2); ?></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Project Cost Summary (if specific company selected) -->
        <?php if ($selected_company_details && !empty($payment_data)): ?>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="fw-bold">Project Cost Summary for <?php echo htmlspecialchars($selected_company_details['cooperator_name']); ?></h5>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="border p-3">
                            <div class="text-muted small">Total Project Cost</div>
                            <div class="fw-bold fs-4">₱<?php echo number_format($selected_company_details['project_cost'], 2); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3">
                            <div class="text-muted small">Total Paid</div>
                            <div class="fw-bold fs-4 text-success">₱<?php echo number_format($total_amount, 2); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3">
                            <div class="text-muted small">Remaining Balance</div>
                            <div class="fw-bold fs-4 <?php echo ($selected_company_details['project_cost'] - $total_amount) > 0 ? 'text-danger' : 'text-success'; ?>">
                                ₱<?php echo number_format(max(0, $selected_company_details['project_cost'] - $total_amount), 2); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer Note -->
        <div class="mt-3 text-muted small">
            <i class="bi bi-info-circle"></i> 
            Showing <?php echo count($payment_data); ?> records. 
            Generated on <?php echo date('F d, Y h:i A'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Print Styles -->
    <style media="print">
        body { background-color: white; padding: 0; }
        .filter-section, .action-buttons, .btn-print, .btn-filter { display: none; }
        .card { box-shadow: none; border: 1px solid #000; }
        .summary-card { background: #f0f0f0 !important; color: black !important; }
        .summary-item { border-right: 1px solid #000; }
        .badge-cash, .badge-check { border: 1px solid #000; }
        .table thead th { background-color: #000 !important; color: white !important; }
        .company-info { border: 1px solid #000; }
    </style>
</body>
>>>>>>> 42f08ab9f1ba9fd8a2b755f4c7f6d241c04ce8d3
</html>