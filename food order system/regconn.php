<!DOCTYPE html>
<html>

<head>
	<title>Insert Page</title>
</head>

<body>
	<center>
	<?php
$conn = mysqli_connect("localhost", "root", "", "food");

if (!$conn) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$Name = $_POST['Name'];
$Phone = $_POST['Phone'];
$Email = $_POST['Email'];
$Pwd = password_hash($_POST['Pwd'], PASSWORD_DEFAULT);

// Validate inputs
if (empty($Name) || empty($Phone) || empty($Email) || empty($Pwd)) {
    die("ERROR: All fields are required.");
}

// Check if email is verified
$verify_conn = new mysqli("localhost", "root", "", "food");
if ($verify_conn->connect_error) {
    die("Verification connection failed: " . $verify_conn->connect_error);
}

$verify_stmt = $verify_conn->prepare("SELECT is_verified FROM verification_codes WHERE email = ? AND is_verified = 1");
$verify_stmt->bind_param("s", $Email);
$verify_stmt->execute();
$verify_result = $verify_stmt->get_result();

if ($verify_result->num_rows == 0) {
    $verify_stmt->close();
    $verify_conn->close();
    echo '<script>alert("Please verify your email before registering."); window.location.href="index.php";</script>';
    exit();
}
$verify_stmt->close();
$verify_conn->close();

// Check for duplicate phone or email
$check_query = "SELECT * FROM users WHERE Phone = '$Phone' OR Email = '$Email'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo '<script>alert("Phone number or Email already exists. Please use a different one."); window.location.href="index.php";</script>';
} else {
    $sql = "INSERT INTO users (Name, Phone, Email, Pwd) VALUES ('$Name', '$Phone', '$Email', '$Pwd')";
    if (mysqli_query($conn, $sql)) {
        // Clear verification status after successful registration
        $verify_conn = new mysqli("localhost", "root", "", "food");
        if ($verify_conn->connect_error) {
            die("Verification connection failed: " . $verify_conn->connect_error);
        }
        
        $clear_stmt = $verify_conn->prepare("DELETE FROM verification_codes WHERE email = ?");
        $clear_stmt->bind_param("s", $Email);
        $clear_stmt->execute();
        $clear_stmt->close();
        $verify_conn->close();
        
        echo '<script>alert("Registration successful!"); window.location.href="login.php";</script>';
    } else {
        echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

	</center>
</body>

</html>