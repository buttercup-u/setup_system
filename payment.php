<?php 
include 'db.php';
$all_cooperators = mysqli_query($conn, "SELECT id, cooperator_name, contact_no FROM companies ORDER BY cooperator_name ASC");
$selected_id = isset($_GET['id']) ? $_GET['id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.E.T.U.P Payment</title>
    <link rel="icon" type="image/png" href="setup.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --brand-blue: #0056b3; --brand-black: #1a1a1a; --bg-gray: #f4f7f6; }
        body { background-color: var(--bg-gray); color: var(--brand-black); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; padding: 20px; }
        
        .card { border: 2px solid var(--brand-black); border-radius: 0; box-shadow: 10px 10px 0px var(--brand-blue); width: 100%; max-width: 480px; background: white; }
        
        .brand-header { text-align: center; border-bottom: 2px solid var(--bg-gray); padding: 20px; margin-bottom: 25px; }
        .main-logo { height: 70px; width: auto; margin-bottom: 8px; }
        .brand-title { color: var(--brand-blue); font-weight: 900; letter-spacing: 1px; margin: 0; font-size: 1.6rem; }
        
        .form-label { font-weight: 800; text-transform: uppercase; font-size: 0.75rem; color: #444; }
        .form-control, .form-select { border: 1.5px solid var(--brand-black); border-radius: 0; padding: 12px; }
        
        .btn-custom { border-radius: 0; font-weight: 800; text-transform: uppercase; border: 2.5px solid var(--brand-black); padding: 14px; transition: 0.2s; }
        .btn-return { background: transparent; color: var(--brand-black); text-decoration: none; display: block; text-align: center; }
        .btn-submit { background: var(--brand-blue); color: white; border-color: var(--brand-blue); }
        .btn-custom:hover { transform: translate(-3px, -3px); }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="card">
    <div class="brand-header">
        <img src="setup.png" alt="S.E.T.U.P Logo" class="main-logo">
        <h2 class="brand-title">PAYMENT</h2>
    </div>
    
    <div class="px-4 pb-5">
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
                    <span class="input-group-text bg-white border-dark fw-bold">₱</span>
                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01">
                </div>
            </div>

            <div id="check_div" class="hidden">
                <div class="mb-3"><label class="form-label">Check No.</label><input type="text" name="check_no" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Bank Name</label><input type="text" name="bank_name" class="form-control"></div>
            </div>

            <div class="row gx-2 mt-4">
                <div class="col-6"><a href="index.php" class="btn btn-return btn-custom w-100">Cancel</a></div>
                <div class="col-6"><button type="submit" class="btn btn-submit btn-custom w-100">Submit</button></div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    const mode = document.getElementById('mode').value;
    const cashDiv = document.getElementById('cash_div');
    const checkDiv = document.getElementById('check_div');
    cashDiv.classList.toggle('hidden', mode !== 'Cash' && mode !== 'Check');
    checkDiv.classList.toggle('hidden', mode !== 'Check');
}
</script>
</body>
</html>