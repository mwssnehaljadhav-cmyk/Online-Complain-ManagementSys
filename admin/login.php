<?php
session_start();
require_once "../db.php";

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

   mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) ==1 )
        {
            $admin = mysqli_fetch_assoc($result);

            if(password_verify($password, $admin['password']))
                {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];

                    header("Location: dashboard.php");
            exit();

        } else {

            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class=" container">
    <h1>Admin Login </h1>

    <?php  if ($error != "") { ?>

        <p style="color: red; text-align: center;">
            <?php echo htmlspecialchars($error); ?>
        </p>

        <?php } ?>

        <form method = "POST">
           <label>Username</label>
        <input type="text"
               name="username"
               placeholder="Enter admin username"
               required>

        <label>Password</label>
        <input type="password"
               name="password"
               placeholder="Enter admin password"
               required>
            <button type="submit" name="login">Login</button>
        </form>
</div>

    
</body>
</html>
          

    
