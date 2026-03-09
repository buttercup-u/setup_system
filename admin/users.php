<?php include 'header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-1" style="color: #1a1a1a;">System Administrators</h2>
        <p class="text-muted mb-0">Manage access and account credentials for the system.</p>
    </div>
    <div class="col-md-6 text-md-right mt-3 mt-md-0">
        <a href="admin_register.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Admin
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-transparent text-muted px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="adminSearch" class="form-control border-0 bg-transparent" placeholder="Search administrator by username...">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-bold">
                            <th class="px-4 py-4 border-0">ACCOUNT ID</th>
                            <th class="py-4 border-0">ADMINISTRATOR</th>
                            <th class="py-4 border-0">ROLE</th>
                            <th class="px-4 py-4 border-0 text-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody">
                        <?php 
                        $users = mysqli_query($conn, "SELECT id, username FROM admins ORDER BY id ASC");
                        while($u = mysqli_fetch_assoc($users)): 
                            // Create a simple initial for the avatar
                            $initial = strtoupper(substr($u['username'], 0, 1));
                        ?>
                        <tr>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.75rem;">#<?php echo str_pad($u['id'], 3, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary-soft text-primary fw-bold me-3">
                                        <?php echo $initial; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($u['username']); ?></div>
                                        <div class="small text-muted" style="font-size: 0.7rem;">Active Account</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-pill bg-dark-soft text-dark px-3" style="font-size: 0.7rem;">SYSTEM ADMIN</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                    <button class="btn btn-white btn-sm px-3 py-2 border-end" title="Edit Permissions" disabled>
                                        <i class="bi bi-shield-lock text-muted"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm px-3 py-2" title="Delete Admin" disabled>
                                        <i class="bi bi-trash text-danger opacity-50"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-light border-start border-4 border-primary" style="border-radius: 0 10px 10px 0;">
            <p class="small text-muted mb-0">
                <i class="bi bi-info-circle-fill text-primary me-2"></i> 
                <strong>Note:</strong> Deletion and permission changes are restricted to the master administrator for security purposes.
            </p>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-dark-soft { background-color: rgba(33, 37, 41, 0.08) !important; }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .btn-white {
        background-color: #fff;
        border: 1px solid #eee;
    }

    .btn-white:hover {
        background-color: #f8f9fa;
    }

    /* Table row hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.02);
    }

    /* Input focus styling */
    #adminSearch:focus {
        box-shadow: none;
    }
</style>

<script>
    // Simple frontend search functionality
    document.getElementById('adminSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#adminTableBody tr');

        rows.forEach(row => {
            let username = row.querySelector('.text-dark').textContent.toLowerCase();
            if (username.indexOf(value) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

<?php include 'footer.php'; ?>