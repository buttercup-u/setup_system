<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.E.T.U.P Registration</title>
    <link rel="icon" type="image/png" href="setup.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --brand-blue: #0056b3; --brand-black: #1a1a1a; --bg-gray: #f4f7f6; }
        body { background-color: var(--bg-gray); color: var(--brand-black); font-family: 'Inter', sans-serif; padding: 40px 20px; }
        
        /* Brutalist Card Design */
        .card { border: 2px solid var(--brand-black); border-radius: 0; box-shadow: 10px 10px 0px var(--brand-blue); background: white; width: 100%; max-width: 750px; margin: auto; transition: 0.3s; }
        
        /* High Visibility Logo Header */
        .brand-header { text-align: center; padding: 20px 0; border-bottom: 2px solid var(--bg-gray); margin-bottom: 30px; }
        .main-logo { height: 80px; width: auto; filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.1)); margin-bottom: 10px; }
        .brand-title { color: var(--brand-blue); font-weight: 900; letter-spacing: 2px; margin: 0; font-size: 1.8rem; }
        .brand-subtitle { font-size: 0.8rem; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        
        /* Form Styling */
        .form-label { font-weight: 800; text-transform: uppercase; font-size: 0.75rem; color: #444; margin-bottom: 6px; }
        .form-control, .form-select { border: 1.5px solid var(--brand-black); border-radius: 0; padding: 12px; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: var(--brand-blue); box-shadow: none; background-color: #fdfdfd; }
        
        /* Button Styling */
        .btn-custom { border-radius: 0; font-weight: 800; text-transform: uppercase; border: 2.5px solid var(--brand-black); transition: 0.2s; padding: 14px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; }
        .btn-register { background: white; color: var(--brand-black); }
        .btn-payment { background: var(--brand-blue); color: white; border-color: var(--brand-blue); }
        .btn-report { background: var(--brand-black); color: white; text-decoration: none; }
        
        .btn-custom:hover { transform: translate(-3px, -3px); box-shadow: 5px 5px 0px rgba(0,0,0,0.15); color: inherit; }
    </style>
</head>
<body>

<div class="card p-4 p-md-5">
    <div class="brand-header">
        <img src="setup.png" alt="S.E.T.U.P Logo" class="main-logo">
        <h2 class="brand-title">REGISTRATION</h2>
        <div class="brand-subtitle">Company Database System • Region 12</div>
    </div>
    
    <form id="mainForm" action="save_company.php" method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Cooperator Name</label>
                <input type="text" name="cooperator_name" class="form-control" placeholder="Company Name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Proprietor / Owner</label>
                <input type="text" name="proprietor" class="form-control" placeholder="Full Name" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Province</label>
            <select name="province" id="province" class="form-select" onchange="updateMunicipalities()" required>
                <option value="" disabled selected>Select Province...</option>
                <option value="South Cotabato">South Cotabato</option>
                <option value="Sultan Kudarat">Sultan Kudarat</option>
                <option value="North Cotabato">North Cotabato</option>
                <option value="SOCCSKSARGEN">SOCCSKSARGEN</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Municipality / City</label>
                <select name="municipality" id="municipality" class="form-select" onchange="updateBarangays()" required disabled>
                    <option value="">Select Municipality...</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Barangay</label>
                <select name="barangay" id="barangay" class="form-select" required disabled>
                    <option value="">Select Barangay...</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-telephone"></i> Contact No.</label>
                <input type="text" name="contact_no" class="form-control" placeholder="09XXXXXXXXX" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="mail@example.com" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Project Title</label>
            <input type="text" name="project_title" class="form-control" placeholder="Project Name" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Project Cost</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-dark fw-bold">₱</span>
                <input type="number" name="project_cost" class="form-control" step="0.01" required>
            </div>
        </div>

        <div class="row gx-2">
            <div class="col-md-4 mb-2">
                <button type="submit" onclick="setFormAction('save_company.php')" class="btn btn-register btn-custom w-100">Register</button>
            </div>
            <div class="col-md-4 mb-2">
                <button type="submit" onclick="setFormAction('payment.php')" class="btn btn-payment btn-custom w-100">
                    <i class="bi bi-credit-card me-2"></i> Payment
                </button>
            </div>
            <div class="col-md-4 mb-2">
                <a href="report.php" class="btn btn-report btn-custom w-100">
                    <i class="bi bi-journal-text me-2"></i> Reports
                </a>
            </div>
        </div>
    </form>
</div>

<div class="text-center mt-4">
    <a href="admin/login.php" class="text-muted small text-decoration-none">Admin Login</a>
</div>

<script>
// Handles switching the action of the form based on button click
function setFormAction(actionUrl) {
    document.getElementById('mainForm').action = actionUrl;
}

const regionData = {
    "South Cotabato": {
        "Koronadal City": ["Poblacion", "Morales", "Carpenter Hill", "Zone I", "Zone II", "Zone III", "Zone IV"],
        "Polomolok": ["Poblacion", "Cannery Site", "Magsaysay", "Sulit", "Lumakil"],
        "Tupi": ["Poblacion", "Crossing Rubber", "Linan", "Lunen"],
        "Banga": ["Poblacion", "Benitez", "Cinco", "El Nonok"],
        "Surallah": ["Poblacion", "Libertad", "Centrala", "Buenavista"]
    },
    "Sultan Kudarat": {
        "Tacurong City": ["Poblacion", "New Isabela", "Buenaflor", "San Pablo", "Griño"],
        "Isulan": ["Poblacion", "Kalawag I", "Kalawag II", "Kalawag III", "Bambad"],
        "Esperanza": ["Poblacion", "Ala", "Dalakiat", "Saliao"],
        "Bagumbayan": ["Poblacion", "Masiag", "Bai Saripinang"]
    },
    "North Cotabato": {
        "Kidapawan City": ["Poblacion", "Apo Sandawa", "Lanao", "Sudapin"],
        "Midsayap": ["Poblacion", "Poblacion 1", "Poblacion 2", "Villarica"],
        "Kabacan": ["Poblacion", "Kayaga", "Kilagasan", "Osias"]
    },
    "SOCCSKSARGEN": {
        "General Santos City": ["Dadiangas East", "Dadiangas North", "Dadiangas South", "Dadiangas West", "Bula", "Calumpang", "Lagao"],
        "Alabel": ["Poblacion", "Bagacay", "Kawas", "Maribulan"],
        "Malungon": ["Poblacion", "Banate", "Kiblat", "Malalag Cogon"],
        "Glan": ["Poblacion", "Burias", "Lago", "Pangyan"]
    }
};

function updateMunicipalities() {
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("municipality");
    const brgySelect = document.getElementById("barangay");
    const selectedProvince = provinceSelect.value;
    citySelect.innerHTML = '<option value="">Select Municipality...</option>';
    brgySelect.innerHTML = '<option value="">Select Barangay...</option>';
    brgySelect.disabled = true;
    if (selectedProvince && regionData[selectedProvince]) {
        citySelect.disabled = false;
        Object.keys(regionData[selectedProvince]).forEach(city => {
            const option = new Option(city, city);
            citySelect.add(option);
        });
    } else {
        citySelect.disabled = true;
    }
}

function updateBarangays() {
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("municipality");
    const brgySelect = document.getElementById("barangay");
    const selectedProvince = provinceSelect.value;
    const selectedCity = citySelect.value;
    brgySelect.innerHTML = '<option value="">Select Barangay...</option>';
    if (selectedCity && regionData[selectedProvince] && regionData[selectedProvince][selectedCity]) {
        brgySelect.disabled = false;
        regionData[selectedProvince][selectedCity].forEach(brgy => {
            const option = new Option(brgy, brgy);
            brgySelect.add(option);
        });
    } else {
        brgySelect.disabled = true;
    }
}
</script>
</body>
</html>