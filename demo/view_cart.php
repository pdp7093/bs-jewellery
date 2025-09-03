<?php
session_start();
$conn = new mysqli("localhost", "root", "", "test");

$product_id = $_POST['product_id'];
$final_price = $_POST['final_price'];
$session_id = session_id();

// Collect customization choices
$customizations = [];
foreach($_POST as $key=>$value){
    if(strpos($key, "custom_") === 0 && $value != ""){
        $customizations[$key] = $value;
    }
}
$customization_json = json_encode($customizations);

// Insert into cart
$stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, customization_details, quantity, total_price) VALUES (?,?,?,?,?)");
$qty = 1;
$stmt->bind_param("sisid", $session_id, $product_id, $customization_json, $qty, $final_price);
$stmt->execute();

echo "Product added to cart successfully! <a href='view_cart.php'>View Cart</a>";
