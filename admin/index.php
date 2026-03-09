<?php include 'header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-1" style="color: #1a1a1a;">Welcome Back, Admin</h2>
                        <p class="text-muted mb-0">Here's the latest overview of the system.</p>
                    </div>
                    <div class="text-right">
                        <div class="p-2 px-3 bg-white shadow-sm d-inline-block" style="border-radius: 10px;">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            <span class="fw-bold"><?php echo date('F d, Y'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: transform 0.3s;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-shape shadow-sm d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background: linear-gradient(45deg, #0056b3, #00aaff); border-radius: 15px;">
                        <i class="bi bi-building text-white fs-4"></i>
                    </div>
                    <div class="ms-3"> <h6 class="text-uppercase text-muted small fw-bold mb-0">Total Registrants</h6>
                        <?php $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM companies")); ?>
                        <h2 class="fw-black mb-0"><?php echo number_format($data['total']); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-shape shadow-sm d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background: linear-gradient(45deg, #198754, #2ecc71); border-radius: 15px;">
                        <i class="bi bi-wallet2 text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-uppercase text-muted small fw-bold mb-0">Revenue Overview</h6>
                        <?php $total_col = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM payments")); ?>
                        <h2 class="fw-black mb-0">₱<?php echo number_format($total_col['total'] ?? 0, 0); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-shape shadow-sm d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background: linear-gradient(45deg, #343a40, #6c757d); border-radius: 15px;">
                        <i class="bi bi-shield-check text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-uppercase text-muted small fw-bold mb-0">Active Admins</h6>
                        <?php $admin_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM admins")); ?>
                        <h2 class="fw-black mb-0"><?php echo $admin_count['total']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h4 class="fw-bold mb-0">Regional Analytics</h4>
            </div>
            <div class="card-body">
                <div id="provinceChart" style="min-height: 380px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h4 class="fw-bold mb-0">By Province</h4>
            </div>
            <div class="card-body">
                <?php
                $locations = ['South Cotabato', 'North Cotabato', 'Sultan Kudarat', 'Gensan and Sarangani'];
                $chart_counts = [];
                $colors = ['#0056b3', '#198754', '#ffc107', '#343a40'];
                foreach($locations as $index => $loc) {
                    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM companies WHERE province = '$loc'"));
                    $count = $row['total'] ?? 0;
                    $chart_counts[] = (int)$count;
                    ?>
                    <div class="d-flex align-items-center justify-content-between p-3 mb-2" style="background: #f8f9fa; border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background: <?php echo $colors[$index]; ?>;" class="me-3"></div>
                            <span class="small fw-bold text-dark"><?php echo $loc; ?></span>
                        </div>
                        <span class="badge bg-white shadow-sm text-dark px-3 rounded-pill"><?php echo $count; ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <h4 class="fw-bold mb-0">Recent Activity</h4>
                <a href="report.php" class="btn btn-light btn-sm rounded-pill px-4 fw-bold">Full Report</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0 px-4 py-3 text-muted small fw-bold">DATE</th>
                                <th class="border-0 py-3 text-muted small fw-bold">COOPERATOR</th>
                                <th class="border-0 py-3 text-muted small fw-bold">MODE</th>
                                <th class="border-0 px-4 py-3 text-end text-muted small fw-bold">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = "SELECT c.cooperator_name, p.registration_date, p.payment_mode, p.amount 
                                      FROM companies c LEFT JOIN payments p ON c.id = p.company_id ORDER BY c.id DESC LIMIT 5";
                            $results = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($results)):
                                $mode_class = (isset($row['payment_mode']) && $row['payment_mode'] == 'Cash') ? 'bg-success-soft text-success' : 'bg-primary-soft text-primary';
                            ?>
                            <tr>
                                <td class="px-4 border-0">
                                    <span class="text-dark fw-bold"><?php echo $row['registration_date'] ? date('M d', strtotime($row['registration_date'])) : '---'; ?></span>
                                    <div class="small text-muted"><?php echo $row['registration_date'] ? date('Y', strtotime($row['registration_date'])) : 'Pending'; ?></div>
                                </td>
                                <td class="border-0 fw-bold text-dark"><?php echo htmlspecialchars($row['cooperator_name']); ?></td>
                                <td class="border-0">
                                    <span class="badge rounded-pill px-3 py-2 <?php echo $mode_class; ?>">
                                        <?php echo $row['payment_mode'] ?? 'Unpaid'; ?>
                                    </span>
                                </td>
                                <td class="px-4 border-0 text-end fw-black text-dark">
                                    ₱<?php echo number_format($row['amount'] ?? 0, 2); ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: <?php echo json_encode($chart_counts); ?>,
        chart: { type: 'donut', height: 380, fontFamily: 'Inherit' },
        labels: <?php echo json_encode($locations); ?>,
        colors: ['#0056b3', '#198754', '#ffc107', '#343a40'],
        stroke: { width: 0 },
        legend: { position: 'bottom', offsetY: 0, fontWeight: 600 },
        plotOptions: { 
            pie: { 
                donut: { 
                    size: '75%',
                    labels: { 
                        show: true, 
                        total: { show: true, label: 'TOTAL', fontWeight: 700, fontSize: '14px', color: '#6c757d' } 
                    } 
                } 
            } 
        },
        dataLabels: { enabled: false }
    };
    new ApexCharts(document.querySelector("#provinceChart"), options).render();
</script>

<?php include 'footer.php'; ?>