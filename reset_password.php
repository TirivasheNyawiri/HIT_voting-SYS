<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['temp_user'])) {
    header("Location: startvoting.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword != $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $con->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashedPassword, $_SESSION['temp_user']['id']);
        if ($stmt->execute()) {
            echo "Password has been reset successfully.";
            unset($_SESSION['temp_user']);
            header("Location: startvoting.php");
            exit();
        } else {
            echo "Error resetting password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        #container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="reset_password.php">
            <label for="new_password">New Password:</label><br>
            <input type="password" name="new_password" placeholder="Enter New Password" required><br><br>
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required><br><br>
            <input type="submit" name="reset" value="Reset Password"><br><br>
        </form>
    </div>
</body>
</html>
