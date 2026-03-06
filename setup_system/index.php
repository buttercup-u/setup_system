<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "setup_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $company_name = $_POST['company_name'];
    $cooperator = $_POST['cooperator'];
    $proprietor_owner = $_POST['proprietor_owner'];
    $address = $_POST['address'];
    $project_title = $_POST['project_title'];
    $project_cost = $_POST['project_cost'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO company_details (company_name, cooperator, proprietor_owner, address, project_title, project_cost) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $company_name, $cooperator, $proprietor_owner, $address, $project_title, $project_cost);

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Data saved successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .message {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Company Details Information</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="company_name" class="required">Company Name</label>
                <input type="text" id="company_name" name="company_name" required 
                       placeholder="Enter company name">
            </div>

            <div class="form-group">
                <label for="cooperator" class="required">Cooperator</label>
                <input type="text" id="cooperator" name="cooperator" required 
                       placeholder="Enter cooperator name">
            </div>

            <div class="form-group">
                <label for="proprietor_owner" class="required">Proprietor Owner</label>
                <input type="text" id="proprietor_owner" name="proprietor_owner" required 
                       placeholder="Enter proprietor/owner name">
            </div>
             <div class="form-group">
                <label for="email_address" class="required">Email Address</label>
                <input type="text" id="email_address" name="email_address" required 
                       placeholder="Enter email address">
            </div>

            <div class="form-group">
                <label for="address" class="required">Address</label>
                <textarea id="address" name="address" required 
                          placeholder="Enter complete address"></textarea>
            </div>

            <div class="form-group">
                <label for="project_title" class="required">Project Title</label>
                <input type="text" id="project_title" name="project_title" required 
                       placeholder="Enter project title">
            </div>

            <div class="form-group">
                <label for="project_cost" class="required">Project Cost</label>
                <input type="number" id="project_cost" name="project_cost" required 
                       step="0.01" min="0" placeholder="Enter project cost">
            </div>

            <button type="submit" name="save">Save Company Details</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>