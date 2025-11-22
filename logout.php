<?php
session_start();
session_destroy();
echo "echo <script>window.location='loginn.php';</script>";
?>
<form action="logout.php" method="post">
    <input type="submit" value="Logout">
</form>
