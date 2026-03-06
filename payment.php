<?php 
include 'db.php';

// Fetch all registered cooperators for the dropdown
$all_cooperators = mysqli_query($conn, "SELECT id, cooperator_name, contact_no FROM companies ORDER BY cooperator_name ASC");

// If coming from index.php, pre-select that specific ID
$selected_id = isset($_GET['id']) ? $_GET['id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.E.T.U.P Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --brand-blue: #0056b3; --brand-black: #1a1a1a; }
        body { background-color: #f4f7f6; color: var(--brand-black); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .card { border: 1px solid var(--brand-black); border-radius: 0; box-shadow: 12px 12px 0px var(--brand-blue); width: 100%; max-width: 450px; background: white; }
        .setup-header { color: var(--brand-blue); font-weight: 900; letter-spacing: 4px; text-align: center; }
        .setup-subheader { text-align: center; font-size: 0.8rem; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 25px; }
        .form-control, .form-select { border: 1px solid #ced4da; border-radius: 0; padding: 10px; }
        .form-label { font-weight: bold; text-transform: uppercase; font-size: 0.75rem; color: #555; }
        .btn-custom { border-radius: 0; font-weight: 700; font-size: 0.85rem; padding: 12px; text-transform: uppercase; }
        .btn-return { border: 2px solid var(--brand-black); background: transparent; }
        .btn-submit { border: 2px solid var(--brand-blue); background: var(--brand-blue); color: white; }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="card p-4 p-md-5">
    <h1 class="setup-header">S.E.T.U.P</h1>
    <div class="setup-subheader">PAYMENT PROCESSING</div>
    
    <form action="process_final.php" method="POST">
        
        <div class="mb-3">
            <label class="form-label">Select Cooperator</label>
            <select name="company_id" class="form-select" required>
                <option value="" disabled <?php echo empty($selected_id) ? 'selected' : ''; ?>>-- Choose Registered --</option>
                <?php while($row = mysqli_fetch_assoc($all_cooperators)): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo ($selected_id == $row['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['cooperator_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Date</label>
            <input type="date" name="reg_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mode of Payment</label>
            <select name="mode" id="mode" class="form-select" onchange="toggleFields()" required>
                <option value="" disabled selected>Select method...</option>
                <option value="Cash">Cash</option>
                <option value="Check">Check</option>
            </select>
        </div>

        <div id="cash_div" class="mb-3 hidden">
            <label class="form-label">Amount</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-dark">₱</span>
                <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01">
            </div>
        </div>

        <div id="check_div" class="hidden">
            <div class="mb-3"><label class="form-label">Check No.</label><input type="text" name="check_no" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Bank Name</label><input type="text" name="bank_name" class="form-control"></div>
        </div>

        <div class="row gx-2 mt-4">
            <div class="col-6"><button type="button" onclick="history.back()" class="btn btn-return btn-custom w-100">Return</button></div>
            <div class="col-6"><button type="submit" class="btn btn-submit btn-custom w-100">Submit</button></div>
        </div>
    </form>
</div>

<script>
function toggleFields() {
    const mode = document.getElementById('mode').value;
    document.getElementById('cash_div').classList.toggle('hidden', mode !== 'Cash');
    document.getElementById('check_div').classList.toggle('hidden', mode !== 'Check');
}
</script>

</body>
</html>