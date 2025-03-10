<?php
@include 'config.php';

// Check cart table structure
$result = mysqli_query($conn, "SHOW CREATE TABLE cart");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "Cart Table Structure:\n";
    echo $row['Create Table'];
} else {
    echo "Error getting table structure: " . mysqli_error($conn);
}

// Check a sample cart entry
$result = mysqli_query($conn, "SELECT * FROM cart LIMIT 1");
if ($result) {
    echo "\n\nSample Cart Entry:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
} else {
    echo "\nError getting sample entry: " . mysqli_error($conn);
}
?>
