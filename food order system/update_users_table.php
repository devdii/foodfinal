<?php
$conn = mysqli_connect('localhost', 'root', '', 'food');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Read SQL file
$sql = file_get_contents('update_users_table.sql');

// Execute SQL
if (mysqli_multi_query($conn, $sql)) {
    echo "Users table updated successfully\n";
} else {
    echo "Error updating users table: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
?>
