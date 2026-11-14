<?php

require_once "../db.php";

$complaint = null ;
$error = "";

if(isset($_POST['track']))
    {
        $complaint_id = trim($_POST['complaint_id']);
        $email = trim($_POST['email']);
    }

    $sql = "SELECT complaints.*, complaint_categories.category_name
            FROM complaints
            JOIN complaint_categories
            ON complaints.category_id = complaint_categories.id
            WHERE complaints.complaint_id = ?
            AND complaints.email = ?";

 $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $complaint_id, $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) > 0) {

        $complaint = mysqli_fetch_assoc($result);

    } else {

        $error = "Invalid Complaint ID or Email Address.";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Complaint</title>
     <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Track your Complaint </h1>
        <form method="POST">
             <label>Complaint ID</label>
        <input type="text"
               name="complaint_id"
               placeholder="Enter Complaint ID"
               required>

        <label>Email Address</label>
        <input type="email"
               name="email"
               placeholder="Enter your email"
               required>

               <button type="submit" name="track">Track Complaint</button>
        </form>
    
        <?php if ($error != "") { ?>

        <p style="color: red;">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php } ?>

     <?php if ($complaint != null) { ?>

        <h2>Complaint Details</h2>

        <p>
            <strong>Complaint ID:</strong>
            <?php echo htmlspecialchars($complaint['complaint_id']); ?>
        </p>

        <p>
            <strong>Customer Name:</strong>
            <?php echo htmlspecialchars($complaint['customer_name']); ?>
        </p>

        <p>
            <strong>Category:</strong>
            <?php echo htmlspecialchars($complaint['category_name']); ?>
        </p>

        <p>
            <strong>Complaint:</strong>
            <?php echo htmlspecialchars($complaint['complaint']); ?>
        </p>

        <p>
            <strong>Status:</strong>
            <?php echo htmlspecialchars($complaint['status']); ?>
        </p>
        <p>
            <strong>Resolution Remarks:</strong>
            <?php echo htmlspecialchars($complaint['resolution_remarks'] ?? 'Not available yet'); ?>
        </p>

    <?php } ?>

    </div>

</body>
</html>