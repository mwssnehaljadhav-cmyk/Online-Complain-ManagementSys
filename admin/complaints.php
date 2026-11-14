<?php

session_start();

require_once __DIR__ . "/../db.php";

if(!isset($_SESSION["admin_id"]))
    {
        header("Location: login.php");
        exit();
    }

$search = isset($_GET["search"]) ? $_GET["search"] :"";
$status = isset($_GET["status"]) ? $_GET["status"] :"";

$searchValue = mysqli_real_escape_string($conn , $search);
$statusValue = mysqli_real_escape_string($conn , $status);  

$sql = "SELECT complaints.*, complaint_categories.category_name
        FROM complaints
        JOIN complaint_categories
        ON complaints.category_id = complaint_categories.id
        WHERE (
            complaints.complaint_id LIKE '%$searchValue%'
            OR complaints.customer_name LIKE '%$searchValue%'
            OR complaints.email LIKE '%$searchValue%'
            OR complaints.complaint LIKE '%$searchValue%'
        )";

if ($statusValue != "") {
    $sql .= " AND complaints.status = '$statusValue'";
}

$result = mysqli_query($conn, $sql);

    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>

    
    <link rel="stylesheet" href="../assets/css/style.css">
     <style>
        .complaints-container {
            max-width: 1300px;
            margin: 40px auto;
            overflow-x: auto;
        }

        .filters {
            display: flex;
            gap: 12px;
            margin: 25px 0;
        }

        .filters input {
            flex: 1;
        }

        table {
              width: 100%;
              min-width: 900px;
              border-collapse: collapse;
              background: white;
              table-layout: auto;
}

        th, td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #263b5e;
            color: white;
        }

        .update-btn {
            display: inline-block;
            background: #263b5e;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .table-container {
    width: 100%;
    overflow-x: auto;
}

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            color: #263b5e;
        }

        @media (max-width: 650px) {
            .filters {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container complaints-container">
    <h1>Manage Complaints</h1>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

  <form method="GET" class="filters">

    <input
        type="text"
        name="search"
        placeholder="Search by ID, name, email or complaint..."
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <select name="status">
        <option value="">All Statuses</option>

        <option value="Pending" <?php if ($status == "Pending") echo "selected"; ?>>
            Pending
        </option>

        <option value="In Progress" <?php if ($status == "In Progress") echo "selected"; ?>>
            In Progress
        </option>

        <option value="Resolved" <?php if ($status == "Resolved") echo "selected"; ?>>
            Resolved
        </option>
    </select>

    <button type="submit">Filter</button>

</form>
<h2>All Complaints</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>  <th>Complaint ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Complaint</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
                    <?php if (mysqli_num_rows($result) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>
    <td><?php echo htmlspecialchars($row['complaint_id']); ?></td>

    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>

    <td><?php echo htmlspecialchars($row['email']); ?></td>

    <td><?php echo htmlspecialchars($row['category_name']); ?></td>

    <td><?php echo htmlspecialchars($row['complaint']); ?></td>

    <td><?php echo htmlspecialchars($row['status']); ?></td>

    <td><?php echo htmlspecialchars($row['created_at']); ?></td>

    <td>
        <a class="update-btn"
           href="update-complaint.php?id=<?php echo (int)$row['id']; ?>">
            Update
        </a>
    </td>
</tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="8" style="text-align:center;">
                    No complaints found.
                </td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</body>
</html>
                    
       