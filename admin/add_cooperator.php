<?php 
include 'header.php'; 

// PHP Logic
$success_id = null;
$success_name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['cooperator_name']);
    $proprietor = mysqli_real_escape_string($conn, $_POST['proprietor']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $municipality = mysqli_real_escape_string($conn, $_POST['municipality']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact_no']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $project_title = mysqli_real_escape_string($conn, $_POST['project_title']);
    $project_cost = mysqli_real_escape_string($conn, $_POST['project_cost']);

    $sql = "INSERT INTO companies (cooperator_name, proprietor, province, municipality, barangay, contact_no, email, project_title, project_cost) 
            VALUES ('$name', '$proprietor', '$province', '$municipality', '$barangay', '$contact', '$email', '$project_title', '$project_cost')";
    
    if (mysqli_query($conn, $sql)) {
        $success_id = mysqli_insert_id($conn);
        echo "
        <div class='alert border-0 shadow-sm mb-4 d-flex justify-content-between align-items-center' style='background: #d1e7dd; border-radius: 15px;'>
            <div class='p-2'>
                <i class='bi bi-check-circle-fill text-success me-2 fs-5'></i> 
                <span class='fw-bold text-success'>Registration Successful!</span>
            </div>
            <a href='payment.php?company_id=$success_id' class='btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm'>
                PROCEED TO PAYMENT <i class='bi bi-arrow-right ms-2'></i>
            </a>
        </div>";
    } else {
        echo "<div class='alert alert-danger border-0 shadow-sm rounded-pill'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-1" style="color: #1a1a1a;">New Cooperator Entry</h2>
        <p class="text-muted mb-0">Fill out the details below to register a new project partner.</p>
    </div>
    <div class="col-md-6 text-md-right mt-3 mt-md-0">
        <a href="cooperators.php" class="btn btn-light rounded-pill px-4 shadow-sm border fw-bold">
            <i class="bi bi-chevron-left me-1"></i> Back to Directory
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form method="POST">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-shape bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">General Information</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Cooperator Name</label>
                            <input type="text" name="cooperator_name" class="form-control custom-input" placeholder="Enter company name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Proprietor / Owner</label>
                            <input type="text" name="proprietor" class="form-control custom-input" placeholder="Full name of owner" required>
                        </div>

                        <div class="col-md-4">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Province</label>
                            <select name="province" id="adm_province" class="form-select custom-input" onchange="updateAdmMunicipalities()" required>
                                <option value="" disabled selected>Select Province</option>
                                <option value="Sultan Kudarat">Sultan Kudarat</option>
                                <option value="South Cotabato">South Cotabato</option>
                                <option value="North Cotabato">North Cotabato</option>
                                <option value="Gensan and Sarangani">Gensan and Sarangani</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Municipality</label>
                            <select name="municipality" id="adm_municipality" class="form-select custom-input" onchange="updateAdmBarangays()" required disabled>
                                <option value="">Select Municipality</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Barangay</label>
                            <select name="barangay" id="adm_barangay" class="form-select custom-input" required disabled>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Contact Number</label>
                            <input type="text" name="contact_no" class="form-control custom-input" placeholder="0912 345 6789" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Email Address</label>
                            <input type="email" name="email" class="form-control custom-input" placeholder="email@address.com" required>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-shape bg-success-soft text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-dark">Project Details</h5>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Project Title</label>
                            <input type="text" name="project_title" class="form-control custom-input" placeholder="Full descriptive title of the project" required>
                        </div>

                        <div class="col-md-4">
                            <label class="small fw-bold text-uppercase text-muted mb-2">Project Cost</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light fw-bold" style="border-radius: 12px 0 0 12px;">₱</span>
                                <input type="number" name="project_cost" class="form-control custom-input border-start-0" step="0.01" style="border-radius: 0 12px 12px 0;" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-5 pt-4 border-top">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Save Cooperator Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(0, 86, 179, 0.1) !important; }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
    
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
        border-color: #0056b3;
        box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.05);
    }
    
    .shadow { box-shadow: 0 10px 25px rgba(0, 86, 179, 0.2) !important; }
</style>

<script>
// Use the same locationData and functions as before
const locationData = {
    "Sultan Kudarat": {
        "Tacurong City": ["Poblacion", "New Isabela", "Buenaflor", "San Pablo", "Griño", "Ledesma"],
        "Isulan": ["Poblacion", "Kalawag I", "Kalawag II", "Kalawag III", "Bambad"],
        "Esperanza": ["Poblacion", "Ala", "Dalakiat", "Saliao"],
        "Bagumbayan": ["Poblacion", "Masiag", "Bai Saripinang"]
    },
    "South Cotabato": {
        "Koronadal City": ["Poblacion", "Morales", "Carpenter Hill", "Zone I", "Zone II", "Zone III"],
        "Polomolok": ["Poblacion", "Cannery Site", "Magsaysay", "Sulit"],
        "Tupi": ["Poblacion", "Crossing Rubber", "Linan", "Lunen"],
        "Banga": ["Poblacion", "Benitez", "Cinco", "El Nonok"]
    },
    "North Cotabato": {
        "Kidapawan City": ["Poblacion", "Apo Sandawa", "Lanao", "Sudapin"],
        "Midsayap": ["Poblacion", "Poblacion 1", "Poblacion 2", "Villarica"],
        "Kabacan": ["Poblacion", "Kayaga", "Kilagasan"]
    },
    "Gensan and Sarangani": {
        "General Santos City": ["Dadiangas East", "Dadiangas North", "Dadiangas South", "Bula", "Calumpang", "Lagao"],
        "Alabel": ["Poblacion", "Bagacay", "Kawas", "Maribulan"],
        "Glan": ["Poblacion", "Burias", "Lago", "Pangyan"]
    }
};

function updateAdmMunicipalities() {
    const province = document.getElementById("adm_province").value;
    const citySelect = document.getElementById("adm_municipality");
    const brgySelect = document.getElementById("adm_barangay");
    
    citySelect.innerHTML = '<option value="">Select Municipality</option>';
    brgySelect.innerHTML = '<option value="">Select Barangay</option>';
    brgySelect.disabled = true;
    
    if (province && locationData[province]) {
        citySelect.disabled = false;
        Object.keys(locationData[province]).forEach(city => {
            let opt = new Option(city, city);
            citySelect.add(opt);
        });
    } else {
        citySelect.disabled = true;
    }
}

function updateAdmBarangays() {
    const province = document.getElementById("adm_province").value;
    const city = document.getElementById("adm_municipality").value;
    const brgySelect = document.getElementById("adm_barangay");
    
    brgySelect.innerHTML = '<option value="">Select Barangay</option>';
    
    if (city && locationData[province] && locationData[province][city]) {
        brgySelect.disabled = false;
        locationData[province][city].forEach(brgy => {
            let opt = new Option(brgy, brgy);
            brgySelect.add(opt);
        });
    } else {
        brgySelect.disabled = true;
    }
}
</script>

<?php include 'footer.php'; ?>