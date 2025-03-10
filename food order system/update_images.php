<?php
@include 'config.php';

$images = ['bu1.jpg', 'bu3.jpg', 'bu5.jpg', 'chef.jpg', 'ceo.jpg'];
$i = 0;

$select_products = mysqli_query($conn, "SELECT * FROM `product`");
if(mysqli_num_rows($select_products) > 0){
    while($fetch_product = mysqli_fetch_assoc($select_products)){
        if($i < count($images)){
            $image = $images[$i];
            $id = $fetch_product['id'];
            mysqli_query($conn, "UPDATE `product` SET image = '$image' WHERE id = $id");
            $i++;
        }
    }
}

echo "Images updated successfully!";
?>
