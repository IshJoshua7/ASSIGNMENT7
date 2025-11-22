<?php
include "connect.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['users'])) {
    echo "<script>window.location='loginn.php';</script>";
    exit;
}

// Helper function
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// INSERT
if (isset($_POST['insert'])) {
    $PhoneNumber = trim($_POST['PhoneNumber']);
    $FirstName   = trim($_POST['FirstName']);
    $LastName    = trim($_POST['LastName']);
    $Gender      = trim($_POST['Gender']);
    $Province    = trim($_POST['Province']);

    if ($PhoneNumber === '' || $FirstName === '' || $LastName === '' || $Gender === '' || $Province === '') {
        $msg = "<p class='error'>All fields are required.</p>";
    } else {
        $stmt = $conne->prepare("INSERT INTO INFORMATION (PhoneNumber, FirstName, LastName, Gender, Province) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $PhoneNumber, $FirstName, $LastName, $Gender, $Province);
        $stmt->execute();
        $stmt->close();
        $msg = "<p class='success'>✅ Record inserted successfully!</p>";
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conne->prepare("DELETE FROM INFORMATION WHERE PhoneNumber = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    $msg = "<p class='warning'>🗑️ Record deleted.</p>";
}

// UPDATE
if (isset($_POST['update'])) {
    $PhoneNumber = $_POST['PhoneNumber'];
    $FirstName   = trim($_POST['FirstName']);
    $LastName    = trim($_POST['LastName']);
    $Gender      = trim($_POST['Gender']);
    $Province    = trim($_POST['Province']);

    $stmt = $conne->prepare("UPDATE INFORMATION SET FirstName = ?, LastName = ?, Gender = ?, Province = ? WHERE PhoneNumber = ?");
    $stmt->bind_param("sssss", $FirstName, $LastName, $Gender, $Province, $PhoneNumber);
    $stmt->execute();
    $stmt->close();
    $msg = "<p class='info'>✏️ Record updated successfully.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Facebook Dashboard</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background: #f0f2f5;
    }
    .header {
        background: #1877f2;
        color: white;
        padding: 15px 30px;
        display:flex;
        justify-content: space-between;
        align-items: center;
    }
    .header h1 { margin:0; font-size:28px; }
    .logout-btn {
        background: white;
        color: #1877f2;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-weight:bold;
    }
    .logout-btn:hover { background:#e4e6eb; }
    .container {
        max-width: 1000px;
        margin: 30px auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow:0 0 10px rgba(0,0,0,0.1);
    }
    h2 { color:#1877f2; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border:1px solid #ddd; padding:8px; text-align:left; }
    th { background:#1877f2; color:white; }
    input[type=text], select, input[type=radio] { padding:6px; }
    input[type=submit] { padding:8px 16px; cursor:pointer; background:#1877f2; color:white; border:none; border-radius:6px; }
    input[type=submit]:hover { background:#145dbf; }
    .btn-delete { color:red; text-decoration:none; font-weight:bold; }
    .btn-delete:hover { text-decoration:underline; }
    .msg { margin:10px 0; font-weight:bold; }
    .error { color:red; }
    .success { color:green; }
    .warning { color:orange; }
    .info { color:blue; }
</style>
</head>
<body>

<div class="header">
    <h1>facebook Dashboard</h1>
    <form action="logout.php" method="post">
        <input type="submit" class="logout-btn" value="Logout">
    </form>
</div>

<div class="container">

<?php if(isset($msg)) echo "<div class='msg'>{$msg}</div>"; ?>

<h2>📋 All Records</h2>
<table>
<tr>
    <th>PhoneNumber</th>
    <th>FirstName</th>
    <th>LastName</th>
    <th>Gender</th>
    <th>Province</th>
    <th>Actions</th>
</tr>

<?php
$result = $conne->query("SELECT * FROM INFORMATION");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <form method="post">
        <tr>
            <td><input type="text" name="PhoneNumber" value="<?php echo h($row['PhoneNumber']); ?>" readonly></td>
            <td><input type="text" name="FirstName" value="<?php echo h($row['FirstName']); ?>"></td>
            <td><input type="text" name="LastName" value="<?php echo h($row['LastName']); ?>"></td>
            <td>
                <select name="Gender">
                    <option value="M" <?php if($row['Gender']=='M') echo 'selected'; ?>>M</option>
                    <option value="F" <?php if($row['Gender']=='F') echo 'selected'; ?>>F</option>
                </select>
            </td>
            <td><input type="text" name="Province" value="<?php echo h($row['Province']); ?>"></td>
            <td>
                <input type="submit" name="update" value="Update">
                <a class="btn-delete" href="?delete_id=<?php echo h($row['PhoneNumber']); ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        </form>
        <?php
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>No records found</td></tr>";
}
?>
</table>

<h2>➕ Add New Record</h2>
<form method="post">
    <label>PhoneNumber: <input type="text" name="PhoneNumber" required></label><br><br>
    <label>FirstName: <input type="text" name="FirstName" required></label><br><br>
    <label>LastName: <input type="text" name="LastName" required></label><br><br>
    <label>Gender:
        <input type="radio" name="Gender" value="M" required>Male
        <input type="radio" name="Gender" value="F">Female
    </label><br><br>
    <label>Province: <input type="text" name="Province" required></label><br><br>
    <input type="submit" name="insert" value="Save Record">
</form>

</div>
</body>
</html>
