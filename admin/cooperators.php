<?php 
include 'header.php'; 
// Ensure db.php is already included via header.php or explicitly here
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-1" style="color: #1a1a1a;">Registered Cooperators</h2>
        <p class="text-muted mb-0">Manage and view individual profile records for all partners.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="add_cooperator.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle-fill me-2"></i> Add New Cooperator
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-transparent text-muted px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="cooperatorSearch" class="form-control border-0 bg-transparent" placeholder="Search by name or company ID...">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-bold">
                            <th class="px-4 py-4 border-0">ACCOUNT ID</th>
                            <th class="py-4 border-0">COOPERATOR NAME</th>
                            <th class="py-4 border-0">STATUS</th>
                            <th class="px-4 py-4 border-0 text-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="cooperatorTableBody">
                        <?php 
                        // Updated table name based on your S.E.T.U.P. structure
                        $query = "SELECT id, cooperator_name FROM companies ORDER BY cooperator_name ASC";
                        $result = mysqli_query($conn, $query);
                        
                        while($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.75rem;">
                                    #<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-dark text-uppercase"><?php echo htmlspecialchars($row['cooperator_name']); ?></div>
                                <div class="small text-muted" style="font-size: 0.7rem;">Verified Member</div>
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-pill bg-success-soft text-success px-3" style="font-size: 0.7rem;">ACTIVE</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                    <a href="print_cooperator.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-white btn-sm px-3 py-2 border-end" title="Print Individual Account">
                                        <i class="bi bi-printer-fill text-primary"></i>
                                    </a>
                                    <button class="btn btn-white btn-sm px-3 py-2" title="Delete Record" disabled>
                                        <i class="bi bi-trash-fill text-danger opacity-50"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
    .btn-white { background-color: #fff; border: 1px solid #eee; }
    .btn-white:hover { background-color: #f8f9fa; }
    .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.02); }
    #cooperatorSearch:focus { box-shadow: none; }
</style>

<script>
    // Live Search Filter
    document.getElementById('cooperatorSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#cooperatorTableBody tr');

        rows.forEach(row => {
            let name = row.querySelector('.text-uppercase').textContent.toLowerCase();
            let id = row.querySelector('.badge').textContent.toLowerCase();
            if (name.indexOf(value) > -1 || id.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

<?php include 'footer.php'; ?>