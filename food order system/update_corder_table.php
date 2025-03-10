<?php
@include 'config.php';

// Read the SQL file
$sql = file_get_contents('update_corder_table.sql');

// Execute the SQL commands
if (mysqli_multi_query($conn, $sql)) {
    echo "Successfully updated corder table structure.<br>";
} else {
    echo "Error updating corder table: " . mysqli_error($conn) . "<br>";
}

// Close the connection
mysqli_close($conn);
?>
