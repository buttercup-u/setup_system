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