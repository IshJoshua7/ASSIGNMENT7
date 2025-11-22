<?php
// CONNECT TO DATABASE
include "connect.php";

$error = "";
$success = "";

if (isset($_POST['submit'])) {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $pass     = trim($_POST['password']);
    $cpass    = trim($_POST['cpassword']);

    // VALIDATION
    if (empty($username) || empty($email) || empty($pass) || empty($cpass)) {
        $error = "All fields are required!";
    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    else if (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters!";
    }
    else if ($pass !== $cpass) {
        $error = "Passwords do not match!";
    }
    else {

        // INSERT INTO DATABASE (plain password)
        $sql = "INSERT INTO users(username,email,password) 
                VALUES('$username','$email','$pass')";

        if (mysqli_query($conne, $sql)) {
            $success = "Account created successfully!";
        } else {
            $error = "Database error: " . mysqli_error($conne);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Facebook Signup</title>
    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
            display:flex;
            justify-content:center;
            padding-top:60px;
        }
        .container {
            width: 400px;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 0px 8px #ccc;
        }
        h1 {
            color: #1877f2;
            text-align:center;
            font-size: 40px;
            font-weight:bold;
        }
        h2 {
            text-align:center;
            margin-top:-10px;
            color:#444;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #1877f2;
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 6px;
            cursor:pointer;
        }
        .error { color: red; text-align:center; font-weight:bold; margin-bottom:10px; }
        .success { color: green; text-align:center; font-weight:bold; margin-bottom:10px; }
        p a { color:#1877f2; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>

<div class="container">

    <h1>facebook</h1>
    <h2>Create a new account</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" 
               name="username" 
               placeholder="Username" 
               value="<?php echo isset($username)?$username:''; ?>">

        <input type="text" 
               name="email" 
               placeholder="Email" 
               value="<?php echo isset($email)?$email:''; ?>">

        <input type="password" name="password" placeholder="New password">
        <input type="password" name="cpassword" placeholder="Confirm password">

        <button type="submit" name="submit">Sign Up</button>

        <p style="text-align:center; margin-top:15px;">
            Already have an account? 
            <a href="loginn.php">Login here</a>
        </p>

    </form>

</div>

</body>
</html>
