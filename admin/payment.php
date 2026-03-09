<?php 
include 'header.php'; 

// Get company_id from URL if coming from add_cooperator.php
$selected_id = isset($_GET['company_id']) ? $_GET['company_id'] : '';

// Handle Payment Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_payment'])) {
    $company_id = mysqli_real_escape_string($conn, $_POST['company_id']);
    $mode = mysqli_real_escape_string($conn, $_POST['payment_mode']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount'] ?? 0);
    $bank = mysqli_real_escape_string($conn, $_POST['bank_name'] ?? '');
    $check = mysqli_real_escape_string($conn, $_POST['check_no'] ?? '');

    $sql = "INSERT INTO payments (company_id, payment_mode, amount, bank_name, check_no, registration_date) 
            VALUES ('$company_id', '$mode', '$amount', '$bank', '$check', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Payment Recorded Successfully!'); window.location='report.php';</script>";
    } else {
        echo "<div class='alert alert-danger border-0 shadow-sm rounded-pill'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-1" style="color: #1a1a1a;">Record Collection</h2>
        <p class="text-muted mb-0">Process payments and finalize registration for cooperators.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form method="POST" id="adminPaymentForm">
                    
                    <div class="text-center mb-5">
                        <div class="icon-shape bg-success-soft text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-cash-stack fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Payment Transaction</h4>
                        <p class="small text-muted text-uppercase fw-bold ls-1">Financial Entry</p>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-uppercase text-muted mb-2 d-block">Cooperator / Company</label>
                        <select name="company_id" class="form-select custom-input" required>
                            <option value="" disabled <?php echo empty($selected_id) ? 'selected' : ''; ?>>-- Choose Company --</option>
                            <?php 
                            $companies = mysqli_query($conn, "SELECT id, cooperator_name FROM companies ORDER BY cooperator_name ASC");
                            while($c = mysqli_fetch_assoc($companies)) {
                                $selected = ($selected_id == $c['id']) ? 'selected' : '';
                                echo "<option value='{$c['id']}' $selected>".htmlspecialchars($c['cooperator_name'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-uppercase text-muted mb-2 d-block">Payment Method</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_mode" id="modeCash" value="Cash" onchange="toggleAdminPaymentFields()" required>
                                <label class="btn btn-outline-primary w-100 py-3 fw-bold" for="modeCash" style="border-radius: 12px;">
                                    <i class="bi bi-wallet2 me-2"></i> CASH
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_mode" id="modeCheck" value="Check" onchange="toggleAdminPaymentFields()">
                                <label class="btn btn-outline-primary w-100 py-3 fw-bold" for="modeCheck" style="border-radius: 12px;">
                                    <i class="bi bi-journal-bookmark me-2"></i> CHECK
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="adminCheckFields" style="display: none; background: #fffcf5; border-radius: 15px;" class="mb-4 p-4 border border-warning border-opacity-25">
                        <h6 class="fw-bold text-warning mb-3 small text-uppercase">Check Details</h6>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control custom-input border-warning border-opacity-25" placeholder="e.g. Landbank / BDO">
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold text-muted">Check Number</label>
                            <input type="text" name="check_no" id="check_no" class="form-control custom-input border-warning border-opacity-25" placeholder="0000000000">
                        </div>
                    </div>

                    <div id="cashAmountWrapper" style="display: none;" class="mb-5 fade-in">
                        <label class="small fw-bold text-uppercase text-muted mb-2 d-block">Amount to Collect</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light fw-bold" style="border-radius: 12px 0 0 12px;">₱</span>
                            <input type="number" name="amount" id="cash_amount" class="form-control custom-input border-start-0" style="border-radius: 0 12px 12px 0;" step="0.01" placeholder="0.00">
                        </div>
                    </div>

                    <button type="submit" name="save_payment" class="btn btn-dark w-100 py-3 fw-bold shadow-lg" style="border-radius: 15px; letter-spacing: 1px;">
                        FINALIZE & RECORD TRANSACTION <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
    .ls-1 { letter-spacing: 1px; }
    
    .custom-input {
        background-color: #f8f9fa;
        border: 2px solid #f8f9fa;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        transition: all 0.2s;
        font-weight: 500;
    }

    .custom-input:focus {
        background-color: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.05);
    }

    /* Animation for appearing fields */
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Style for the Radio Buttons as cards */
    .btn-check:checked + .btn-outline-primary {
        background-color: #0d6efd !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
</style>

<script>
function toggleAdminPaymentFields() {
    const isCash = document.getElementById('modeCash').checked;
    const isCheck = document.getElementById('modeCheck').checked;
    
    const checkFields = document.getElementById('adminCheckFields');
    const cashWrapper = document.getElementById('cashAmountWrapper');
    
    const bankInput = document.getElementById('bank_name');
    const checkInput = document.getElementById('check_no');
    const amountInput = document.getElementById('cash_amount');

    if (isCash) {
        cashWrapper.style.display = 'block';
        amountInput.required = true;
        
        checkFields.style.display = 'none';
        bankInput.required = false;
        checkInput.required = false;
        bankInput.value = "";
        checkInput.value = "";
    } 
    else if (isCheck) {
        checkFields.style.display = 'block';
        cashWrapper.style.display = 'block'; // Checks still need an amount recorded
        
        bankInput.required = true;
        checkInput.required = true;
        amountInput.required = true;
    }
}
</script>

<?php include 'footer.php'; ?>