<?php
session_start();
$conn = new mysqli("localhost", "root", "", "test");

// Get product
$product_id = 1; // Example product
$product = $conn->query("SELECT * FROM products WHERE product_id=$product_id")->fetch_assoc();

// Get customizations
$customizations = $conn->query("
    SELECT c.customization_id, c.customization_name, c.customization_type
    FROM product_customizations pc
    JOIN customizations c ON pc.customization_id=c.customization_id
    WHERE pc.product_id=$product_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['product_name']; ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .product-page { display: flex; gap: 20px; }
        .product-images img { border: 1px solid #ccc; padding: 5px; }
        .product-details { max-width: 400px; }
        label { font-weight: bold; display: block; margin-top: 10px; }
    </style>
</head>
<body>

<div class="product-page">
    <div class="product-images">
        <img src="images/<?php echo $product['image']; ?>" width="300">
    </div>

    <div class="product-details">
        <h2><?php echo $product['product_name']; ?></h2>
        <p><?php echo $product['description']; ?></p>
        <h3>Price: ₹<span id="totalPrice"><?php echo $product['price']; ?></span></h3>

        <form method="post" action="add_to_cart.php" id="customForm">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" id="finalPrice" name="final_price" value="<?php echo $product['price']; ?>">

            <?php while($c = $customizations->fetch_assoc()): ?>
                <label><?php echo $c['customization_name']; ?>:</label>

                <?php if($c['customization_type']=="dropdown"): ?>
                    <select name="custom_<?php echo $c['customization_id']; ?>" class="customOption" data-custid="<?php echo $c['customization_id']; ?>">
                        <option value="" data-price="0">Select</option>
                        <?php
                            $options = $conn->query("SELECT * FROM customization_options WHERE customization_id=".$c['customization_id']);
                            while($o = $options->fetch_assoc()):
                        ?>
                            <option value="<?php echo $o['option_value']; ?>" data-price="<?php echo $o['extra_price']; ?>">
                                <?php echo $o['option_value']; ?> (+₹<?php echo $o['extra_price']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                <?php elseif($c['customization_type']=="text"): ?>
                    <input type="text" name="custom_<?php echo $c['customization_id']; ?>" placeholder="Enter text">
                <?php endif; ?>
            <?php endwhile; ?>

            <br><br>
            <button type="submit">Add to Cart</button>
        </form>
    </div>
</div>

<script>
let basePrice = parseFloat($("#totalPrice").text());

$(document).on("change", ".customOption", function(){
    let total = basePrice;
    $(".customOption option:selected").each(function(){
        total += parseFloat($(this).data("price") || 0);
    });
    $("#totalPrice").text(total);
    $("#finalPrice").val(total);
});
</script>

</body>
</html>
