
<?php
session_start();
require_once "../db.php";

// Check whether admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get total complaints
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints");
$total = mysqli_fetch_assoc($totalQuery)['total'];

// Get pending complaints
$pendingQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status = 'Pending'");
$pending = mysqli_fetch_assoc($pendingQuery)['total'];

// Get in-progress complaints
$progressQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status = 'In Progress'");
$progress = mysqli_fetch_assoc($progressQuery)['total'];

// Get resolved complaints
$resolvedQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status = 'Resolved'");
$resolved = mysqli_fetch_assoc($resolvedQuery)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .dashboard {
            max-width: 1100px;
            margin: 40px auto;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .stat-card h2 {
            color: #263b5e;
            font-size: 32px;
        }

        .stat-card p {
            color: #555;
            font-weight: bold;
        }

        .dashboard a {
            text-decoration: none;
        }

        .dashboard-btn {
            display: inline-block;
            background: #263b5e;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            margin-right: 10px;
        }

        .logout {
            background: #dc3545;
        }

        @media (max-width: 700px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 450px) {
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container dashboard">

    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>

        <p>
            Welcome,
            <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
        </p>
    </div>

    <h2>Complaint Statistics</h2>

    <div class="stats">

        <div class="stat-card">
            <h2><?php echo $total; ?></h2>
            <p>Total Complaints</p>
        </div>

        <div class="stat-card">
            <h2><?php echo $pending; ?></h2>
            <p>Pending</p>
        </div>

        <div class="stat-card">
            <h2><?php echo $progress; ?></h2>
            <p>In Progress</p>
        </div>

        <div class="stat-card">
            <h2><?php echo $resolved; ?></h2>
            <p>Resolved</p>
        </div>

    </div>

    <h2>Manage Complaints</h2>

    <a href="complaints.php" class="dashboard-btn">
        View All Complaints
    </a>

    <a href="logout.php" class="dashboard-btn logout">
        Logout
    </a>

</div>

</body>
</html>
