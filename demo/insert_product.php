<?php
$conn = new mysqli("localhost", "root", "", "bs-jewellery");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $product_name   = $_POST['product_name'];
    $product_code   = $_POST['product_code'];
    $product_decsp  = $_POST['product_decsp'];
    $price          = $_POST['price'];
    $gender         = $_POST['gender'];
    $height         = $_POST['height'];
    $width          = $_POST['width'];
    $product_wieght = $_POST['product_wieght'];
    $cate_id        = $_POST['cate_id'];
    $collection_id  = $_POST['collection_id'];
    $metals_id      = $_POST['metals_id'];
    $diamonds_id    = $_POST['diamonds_id'];
    $of_stones      = $_POST['of_stones'];
    $diamond_weight = $_POST['diamond_weight'];

    // Handle multiple images
    $image_names = [];
    if (!empty($_FILES['product_image']['name'][0])) {
        foreach ($_FILES['product_image']['name'] as $key => $name) {
            $tmp_name = $_FILES['product_image']['tmp_name'][$key];
            $new_name = time() . "_" . $name;
            $target = "uploads/" . $new_name;

            if (move_uploaded_file($tmp_name, $target)) {
                $image_names[] = $new_name;
            }
        }
    }

    // Store images as JSON
    $images = json_encode($image_names);

    // 1️⃣ Insert into database
    $sql = "INSERT INTO product 
    (cate_id, collection_id, product_name, product_code, product_image, product_decsp, price, gender, height, width, product_wieght, metals_id, diamonds_id, of_stones, diamond_weight) 
    VALUES 
    ('$cate_id', '$collection_id', '$product_name', '$product_code', '$images', '$product_decsp', '$price', '$gender', '$height', '$width', '$product_wieght', '$metals_id', '$diamonds_id', '$of_stones', '$diamond_weight')";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Product inserted into DB.<br>";

        // 2️⃣ Send data to API
        $postData = [
            "cate_id"        => $cate_id,
            "collection_id"  => $collection_id,
            "product_name"   => $product_name,
            "product_code"   => $product_code,
            "product_image"  => $images,
            "product_decsp"  => $product_decsp,
            "price"          => $price,
            "gender"         => $gender,
            "height"         => $height,
            "width"          => $width,
            "product_wieght" => $product_wieght,
            "metals_id"      => $metals_id,
            "diamonds_id"    => $diamonds_id,
            "of_stones"      => $of_stones,
            "diamond_weight" => $diamond_weight
        ];

        $ch = curl_init("http://localhost/bs-jewellery/Admin/add_product");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);
        curl_close($ch);

        echo "🌐 API Response: " . $response;
    } else {
        echo "❌ DB Error: " . $conn->error;
    }
}
?>
