<?php
session_start();
require_once "../db.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get complaint ID from the URL
if (!isset($_GET['id'])) {
    die("Complaint ID is missing.");
}

$id = intval($_GET['id']);

// Update complaint when the form is submitted
if (isset($_POST['update'])) {

    $status = $_POST['status'];
    $remarks = $_POST['resolution_remarks'];

    $sql = "UPDATE complaints
            SET status = ?, resolution_remarks = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ssi", $status, $remarks, $id);

    if (mysqli_stmt_execute($stmt)) {

        // Go back to complaints page after updating
        header("Location: complaints.php?updated=1");
        exit();

    } else {
        echo "Unable to update the complaint.";
    }
}

// Get the existing complaint details
$sql = "SELECT * FROM complaints WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$complaint = mysqli_fetch_assoc($result);

// Check whether the complaint exists
if (!$complaint) {
    die("Complaint not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Complaint</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
         <h1>Update Complaint</h1>

    <p>
        <strong>Complaint ID:</strong>
        <?php echo htmlspecialchars($complaint['complaint_id']); ?>
    </p>

    <p>
        <strong>Customer Name:</strong>
        <?php echo htmlspecialchars($complaint['customer_name']); ?>
    </p>

 <form method="POST">

        <label>Complaint Status</label>

        <select name="status" required>

            <option value="Pending"
                <?php if ($complaint['status'] == "Pending") echo "selected"; ?>>
                Pending
            </option>

            <option value="In Progress"
                <?php if ($complaint['status'] == "In Progress") echo "selected"; ?>>
                In Progress
            </option>

            <option value="Resolved"
                <?php if ($complaint['status'] == "Resolved") echo "selected"; ?>>
                Resolved
            </option>

        </select>

        <label>Resolution Remarks</label>
        <textarea name="resolution_remarks" placeholder="Enter resolution remarks"><?php echo htmlspecialchars($complaint['resolution_remarks'] ?? ''); ?></textarea>

        <button type="submit" name="update">Update Complaint</button>
    </form>

    <p>
        <a href="complaints.php"> Back to Complaints</a>
    </p>
    </div>
    
</body>
</html>