<div class="sidebar bg-dark text-white" style="min-height: 100vh; width: 250px; position: fixed; top: 0; left: 0; padding-top: 30px; border-right: 1px solid rgba(255,255,255,0.1);">
    
    div class="px-4 mb-4">
    <a href="index.php" class="text-decoration-none text-white">
        
        <h4 class="fw-bold mb-1 text-white" style="letter-spacing:2px;">
            S.E.T.U.P.
        </h4>

        <small class="text-muted text-uppercase fw-bold"
               style="font-size:0.6rem; letter-spacing:1.5px; opacity:0.7;">
            Internal Admin Portal
        </small>

    </a>
</div>

    <ul class="nav flex-column mt-4">

        <li class="nav-item mb-1">
            <a class="nav-link text-white py-3 px-4 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">
                <i class="bi bi-speedometer2 me-3"></i> Dashboard
            </a>
        </li>

        <li class="nav-item mb-1">
            <a class="nav-link text-white py-3 px-4 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'cooperators.php' || basename($_SERVER['PHP_SELF']) == 'add_cooperator.php') ? 'active' : ''; ?>" href="cooperators.php">
                <i class="bi bi-building me-3"></i> Cooperators
            </a>
        </li>

        <li class="nav-item mb-1">
            <a class="nav-link text-white py-3 px-4 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'payment.php') ? 'active' : ''; ?>" href="payment.php">
                <i class="bi bi-credit-card me-3"></i> Payment
            </a>
        </li>

        <li class="nav-item mb-1">
            <a class="nav-link text-white py-3 px-4 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'report.php') ? 'active' : ''; ?>" href="report.php">
                <i class="bi bi-file-earmark-bar-graph me-3"></i> Reports
            </a>
        </li>

        <li class="nav-item mb-1">
            <a class="nav-link text-white py-3 px-4 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>" href="users.php">
                <i class="bi bi-shield-lock me-3"></i> Admin Staff
            </a>
        </li>

        <li class="mt-4 px-4 mb-2">
            <small class="text-muted text-uppercase fw-bold" style="font-size:0.65rem; letter-spacing:2px;">
                System Control
            </small>
        </li>

        <li class="nav-item mb-1">
            <a class="nav-link text-white py-3 px-4 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>" href="settings.php">
                <i class="bi bi-gear me-3"></i> Settings
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-danger py-3 px-4 d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right me-3"></i> Sign Out
            </a>
        </li>

    </ul>
</div>


<style>

body{
    padding-left:250px;
    background-color:#f8f9fa;
}

.setup-logo{
    height:35px;
    width:auto;
    margin-right:10px;
    object-fit:contain;
    display:none;
}

.nav-link{
    transition:all 0.2s ease;
    opacity:0.8;
    font-weight:500;
    border-left:4px solid transparent;
}

.nav-link:hover{
    background:rgba(255,255,255,0.05);
    opacity:1;
    color:#fff !important;
}

.nav-link.active{
    opacity:1 !important;
    border-left:4px solid #0d6efd;
    background-color:rgba(13,110,253,0.15) !important;
    color:#0d6efd !important;
}

.bi{
    font-size:1.1rem;
}

</style>