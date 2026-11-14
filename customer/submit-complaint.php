
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../db.php";

$success = "";
$error = "";
$complaintID = "";

// Handle form submission
if (isset($_POST['submit'])) {

    $name = trim($_POST['customer_name']);
    $email = trim($_POST['email']);
    $category = (int) $_POST['category_id'];
    $complaint = trim($_POST['complaint']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        // Generate unique complaint ID
        $complaintID = "CMP-" . date("Ymd") . "-" .
                       strtoupper(bin2hex(random_bytes(3)));

        $sql = "INSERT INTO complaints
                (complaint_id, customer_name, email, category_id, complaint)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssis",
            $complaintID,
            $name,
            $email,
            $category,
            $complaint
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "Your complaint has been submitted successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}

// Fetch complaint categories
$sql = "SELECT * FROM complaint_categories";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">

    <h1>Online Complaint Management System</h1>
    <h2>Submit Your Complaint</h2>

    <?php if ($success) { ?>

        <p style="color: green; text-align: center;">
            <?php echo $success; ?>
        </p>

        <h3 style="text-align: center;">
            Your Complaint ID:
            <?php echo htmlspecialchars($complaintID); ?>
        </h3>

    <?php } ?>

    <?php if ($error) { ?>

        <p style="color: red; text-align: center;">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php } ?>

    <form action="submit-complaint.php" method="POST">

        <label>Full Name</label>

        <input type="text"
               name="customer_name"
               placeholder="Enter your name"
               required>

        <label>Email Address</label>

        <input type="email"
               name="email"
               placeholder="Enter your email"
               required>

        <label>Complaint Category</label>

        <select name="category_id" required>

            <option value="">Select Category</option>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <option value="<?php echo $row['id']; ?>">
                    <?php echo htmlspecialchars($row['category_name']); ?>
                </option>

            <?php } ?>

        </select>

        <label>Complaint Details</label>

        <textarea name="complaint"
                  placeholder="Describe your complaint"
                  required></textarea>

        <button type="submit" name="submit">
            Submit Complaint
        </button>

    </form>

</div>

</body>
</html>
