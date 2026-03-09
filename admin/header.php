<?php
session_start();
include '../db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>S.E.T.U.P | Admin Portal</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="../setup.png">
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* FIXED: Text-based logo styling to replace broken image */
        .navbar-brand-text { 
            color: #ffffff !important; 
            font-weight: 800; 
            letter-spacing: 2px; 
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .navbar-brand-text i { color: #0056b3; margin-right: 10px; }
        
        .app-navbar { background: #1a1a1a; border-right: 4px solid #0056b3; }
        .nav-title { text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700; }
    </style>
</head>

<body>
    <div class="app">
        <div class="app-wrap">
            <header class="app-header top-bar">
                <nav class="navbar navbar-expand-md">
                    <div class="navbar-header d-flex align-items-center">
                        <a href="javascript:void(0);" class="mobile-toggle"><i class="ti ti-align-right"></i></a>
                        <a class="navbar-brand-text" href="index.php">
                            <i class="bi bi-cpu-fill"></i> S.E.T.U.P
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="navigation d-flex">
                            <ul class="navbar-nav nav-left">
                                <li class="nav-item">
                                    <a href="javascript:void(0)" class="nav-link sidebar-toggle">
                                        <i class="ti ti-align-right"></i>
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav nav-right ml-auto">
                                <li class="nav-item dropdown user-profile">
                                    <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                    </a>
                                    <div class="dropdown-menu animated fadeIn">
                                        <div class="p-3 shadow-lg bg-white text-center">
                                            <h6 class="mb-0">Administrator</h6>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="logout.php"><i class="ti ti-unlock"></i> Logout</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <div class="app-container">
                <aside class="app-navbar">
                    <div class="sidebar-nav scrollbar scroll_light">
                        <ul class="metismenu" id="sidebarNav">
                            <li class="nav-static-title">Main Menu</li>
                            <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                                <a href="index.php"><i class="nav-icon bi bi-grid-1x2-fill"></i><span class="nav-title">Dashboard</span></a>
                            </li>
                            <li class="<?php echo ($current_page == 'cooperators.php') ? 'active' : ''; ?>">
                                <a href="cooperators.php"><i class="nav-icon bi bi-building"></i><span class="nav-title">Cooperators</span></a>
                            </li>
                            <li class="<?php echo ($current_page == 'payment.php') ? 'active' : ''; ?>">
                                <a href="payment.php"><i class="nav-icon bi bi-cash-coin"></i><span class="nav-title">Payment</span></a>
                            </li>
                            <li class="<?php echo ($current_page == 'report.php') ? 'active' : ''; ?>">
                                <a href="report.php"><i class="nav-icon bi bi-file-earmark-bar-graph"></i><span class="nav-title">Reports</span></a>
                            </li>
                            <li class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                                <a href="users.php"><i class="nav-icon bi bi-people-fill"></i><span class="nav-title">Admin Users</span></a>
                            </li>
                        </ul>
                    </div>
                </aside>

                <div class="app-main" id="main">
                    <div class="container-fluid">