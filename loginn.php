<?php
include("connect.php");
session_start();

$error = "";

if (isset($_POST['login'])) {

    $user_input = trim($_POST['user_input']);
    $password   = trim($_POST['password']);

    // EMPTY FIELDS
    if (empty($user_input) || empty($password)) {
        $error = "All fields are required!";
    } else {

        // CHECK USER BY EMAIL OR USERNAME
        $query = "SELECT * FROM users 
                  WHERE email='$user_input' 
                  OR username='$user_input' 
                  LIMIT 1";

        $run = mysqli_query($conne, $query);

        // ACCOUNT NOT FOUND
        if (mysqli_num_rows($run) == 0) {
            $error = "Account not found!";
        } else {

            $row = mysqli_fetch_assoc($run);

            // CHECK PASSWORD AS PLAIN TEXT
            if ($password !== $row['password']) {
                $error = "Wrong password!";
            } else {

                // LOGIN SUCCESS
                $_SESSION['user'] = $row['username'];
                echo "<script>window.location='ASSIGNMENT.php';</script>";
                exit;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login</title>
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
        p a { color:#1877f2; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>

<div class="container">

    <h1>facebook</h1>
    <h2>Login to your account</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" 
               name="user_input" 
               placeholder="Email or Username">

        <input type="password" 
               name="password" 
               placeholder="Password">

        <button type="submit" name="login">Sign In</button>

        <!-- LINK TO SIGN UP PAGE -->
        <p style="text-align:center; margin-top:15px;">
            Don't have an account? 
            <a href="signup.php">Create a new account</a>
        </p>

    </form>

</div>

</body>
</html>
