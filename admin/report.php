<?php 
include 'header.php'; 

// 1. Capture Filter Inputs
$selected_month = isset($_GET['month']) ? $_GET['month'] : '';
$selected_year = isset($_GET['year']) ? $_GET['year'] : '';
$selected_company = isset($_GET['company_id']) ? $_GET['company_id'] : '';

// 2. Fetch Data for Dropdowns
$companies_query = mysqli_query($conn, "SELECT id, cooperator_name FROM companies ORDER BY cooperator_name ASC");
$years_query = mysqli_query($conn, "SELECT DISTINCT YEAR(registration_date) as year FROM payments WHERE registration_date IS NOT NULL UNION SELECT DISTINCT YEAR(registration_date) FROM payments ORDER BY year DESC");
$years = []; 
while ($y = mysqli_fetch_assoc($years_query)) { if($y['year']) $years[] = $y['year']; }

// 3. Construct Filter Query
$where_conditions = [];
if (!empty($selected_company)) { $where_conditions[] = "c.id = '" . mysqli_real_escape_string($conn, $selected_company) . "'"; }
if (!empty($selected_month)) { $where_conditions[] = "MONTH(p.registration_date) = '" . mysqli_real_escape_string($conn, $selected_month) . "'"; }
if (!empty($selected_year)) { $where_conditions[] = "YEAR(p.registration_date) = '" . mysqli_real_escape_string($conn, $selected_year) . "'"; }
$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : '';

// 4. Main Query (Added p.id as pid for the receipt link)
$query = "SELECT c.cooperator_name, c.municipality, c.id as cid, p.id as pid, p.registration_date, p.payment_mode, p.amount 
          FROM companies c 
          LEFT JOIN payments p ON c.id = p.company_id 
          $where_clause 
          ORDER BY p.registration_date DESC, c.cooperator_name ASC";

$results = mysqli_query($conn, $query);
$payment_data = []; 
$total_amount = 0; 
$cash_total = 0;
$paid_count = 0;

while ($row = mysqli_fetch_assoc($results)) {
    $payment_data[] = $row;
    if (isset($row['amount']) && $row['amount'] > 0) {
        $total_amount += (float)$row['amount'];
        $paid_count++;
        if ($row['payment_mode'] == 'Cash') { $cash_total += (float)$row['amount']; }
    }
}

$month_names = [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec'];
?>

<div class="d-none d-print-block mb-5">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <div>
            <img src="../setup.png" style="height: 50px;">
            <h3 class="fw-bold mb-0">S.E.T.U.P. COLLECTION REPORT</h3>
            <p class="text-muted small mb-0">Internal Financial Summary | South Cotabato Region</p>
        </div>
        <div class="text-end">
            <p class="small mb-0">Report Date: <?php echo date('F d, Y'); ?></p>
            <p class="small mb-0">Admin: <?php echo strtoupper($_SESSION['username'] ?? 'User'); ?></p>
        </div>
    </div>
</div>

<div class="row mb-4 align-items-center no-print">
    <div class="col-md-6">
        <h2 class="fw-bold mb-1" style="color: #1a1a1a;">Collection Reports</h2>
        <p class="text-muted mb-0">Track revenue and print individual receipts for partners.</p>
    </div>
    </div>

<div class="card border-0 shadow-sm mb-4 no-print" style="border-radius: 15px;">
    <div class="card-body p-3">
        <form method="GET" action="report.php" class="row g-2 align-items-end">
            <div class="col-md-4">
                <select name="company_id" class="form-select border-0 bg-light rounded-pill px-3">
                    <option value="">All Companies</option>
                    <?php mysqli_data_seek($companies_query, 0); while($c = mysqli_fetch_assoc($companies_query)): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo ($selected_company == $c['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['cooperator_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="month" class="form-select border-0 bg-light rounded-pill px-3">
                    <option value="">All Months</option>
                    <?php foreach($month_names as $num => $name): ?>
                        <option value="<?php echo $num; ?>" <?php echo ($selected_month == $num) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="year" class="form-select border-0 bg-light rounded-pill px-3">
                    <option value="">All Years</option>
                    <?php foreach($years as $y): ?>
                        <option value="<?php echo $y; ?>" <?php echo ($selected_year == $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div>
                    <p class="small fw-bold text-muted mb-0 text-uppercase">Total Collections</p>
                    <h3 class="fw-black mb-0">₱<?php echo number_format($total_amount, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-warning-soft text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <p class="small fw-bold text-muted mb-0 text-uppercase">Paid Registrants</p>
                    <h3 class="fw-black mb-0"><?php echo $paid_count; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-success-soft text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <div>
                    <p class="small fw-bold text-muted mb-0 text-uppercase">Cash Liquidity</p>
                    <h3 class="fw-black mb-0">₱<?php echo number_format($cash_total, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-muted small fw-bold">
                    <th class="px-4 py-4 border-0">TRANSACTION DATE</th>
                    <th class="py-4 border-0">COOPERATOR</th>
                    <th class="py-4 border-0 text-center">MODE</th>
                    <th class="py-4 border-0 text-end">COLLECTED</th>
                    <th class="px-4 py-4 border-0 text-end no-print">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($payment_data)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No records found.</td>
                    </tr>
                <?php else: foreach($payment_data as $p): ?>
                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">
                        <?php echo $p['registration_date'] ? date('M d, Y', strtotime($p['registration_date'])) : '<span class="badge bg-light text-muted fw-normal">PENDING</span>'; ?>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-primary text-uppercase" style="font-size: 0.85rem;"><?php echo htmlspecialchars($p['cooperator_name']); ?></div>
                        <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($p['municipality']); ?></div>
                    </td>
                    <td class="py-3 text-center">
                        <?php if($p['payment_mode']): ?>
                            <span class="badge rounded-pill px-3 py-2 <?php echo $p['payment_mode'] == 'Cash' ? 'bg-success-soft text-success' : 'bg-info-soft text-info'; ?>" style="font-size: 0.7rem;">
                                <?php echo strtoupper($p['payment_mode']); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-light text-muted rounded-pill px-3 py-2" style="font-size: 0.7rem;">UNPAID</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 text-end fw-black">
                        <?php echo $p['amount'] ? '₱'.number_format($p['amount'], 2) : '₱0.00'; ?>
                    </td>
                    <td class="px-4 py-3 text-end no-print">
                        <?php if(!empty($p['pid'])): ?>
                            <a href="print_receipt.php?id=<?php echo $p['pid']; ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 shadow-sm">
                                <i class="bi bi-printer-fill me-1"></i> Receipt
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-light rounded-pill px-3 opacity-50" disabled>No Pymt</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
            <tfoot class="bg-dark text-white fw-bold">
                <tr>
                    <td colspan="3" class="text-end py-3 px-4 text-uppercase">Report Total:</td>
                    <td class="text-end px-4 py-3 h5 mb-0 fw-black">₱<?php echo number_format($total_amount, 2); ?></td>
                    <td class="no-print"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="d-none d-print-block mt-5 pt-5">
    <div class="row text-center">
        <div class="col-4">
            <div class="border-top pt-2 small fw-bold">Prepared By</div>
            <p class="small"><?php echo strtoupper($_SESSION['username'] ?? 'User'); ?></p>
        </div>
        <div class="col-4 ms-auto">
            <div class="border-top pt-2 small fw-bold">Noted By</div>
            <p class="small">S.E.T.U.P. Administrator</p>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1) !important; }
    .fw-black { font-weight: 900; }
    
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; margin: 0; padding: 15mm; }
        .card { border: none !important; box-shadow: none !important; }
        .table { border: 1px solid #000; }
        .bg-dark { background-color: #000 !important; color: #fff !important; }
    }
</style>

<?php include 'footer.php'; ?>