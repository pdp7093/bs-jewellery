
<?php
// DB Connection
$conn = new mysqli("localhost", "root", "", "bs-jewellery");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch dropdown data
// $categories = $conn->query("SELECT cate_id, category_name FROM category");

$categoryAPI = file_get_contents("http://localhost/bs-jewellery/Admin/view_category");
$categoryResult = json_decode($categoryAPI, true);

$collectionAPI = file_get_contents("http://localhost/bs-jewellery/Admin/Manage_collection");
$collectionResult = json_decode($collectionAPI, true);

$metalAPI = file_get_contents("http://localhost/bs-jewellery/Admin/Manage_metal");
$metalResult = json_decode($metalAPI, true);

$diamondAPI = file_get_contents("http://localhost/bs-jewellery/Admin/Manage_diamonds");
$diamondResult = json_decode($diamondAPI, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
    <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f9fc;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 6px;
      color: #555;
    }
    input[type="text"],
    input[type="number"],
    textarea,
    select {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    input[type="file"] {
      margin-top: 8px;
    }
    textarea {
      resize: vertical;
      height: 80px;
    }
    button {
      display: block;
      width: 100%;
      padding: 10px;
      background: #007bff;
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 15px;
    }
    button:hover {
      background: #0056b3;
    }
  </style>

</head>
<body>
<div class="container">
  <h2>Add New Product</h2>
  <form action="http://localhost/bs-jewellery/Admin/Add_product" method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="product_name" required>
    </div>

    <div class="form-group">
      <label>Product Code</label>
      <input type="text" name="product_code" required>
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="product_decsp"></textarea>
    </div>

    <div class="form-group">
      <label>Price</label>
      <input type="number" step="0.01" name="price" required>
    </div>

    <div class="form-group">
      <label>Gender</label>
      <select name="gender">
        <option value="">-- Select Gender --</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="unisex">Unisex</option>
      </select>
    </div>

    <div class="form-group">
      <label>Height</label>
      <input type="number" name="height">
    </div>

    <div class="form-group">
      <label>Width</label>
      <input type="number" name="width">
    </div>

    <div class="form-group">
      <label>Product Weight</label>
      <input type="number" step="0.01" name="product_wieght">
    </div>

    <div class="form-group">
    <label>Category</label>
    <select name="cate_id" required>
    <option value="">-- Select Category --</option>
    <?php if ($categoryResult['status'] && !empty($categoryResult['data'])): ?>
        <?php foreach($categoryResult['data'] as $row): ?>
            <option value="<?= $row['cate_id'] ?>"><?= $row['category_name'] ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
    </select>
    </div>

    <div class="form-group">
    <label>Collection</label>
    <select name="collection_id" required>
    <option value="">-- Select Collection --</option>
    <?php if ($collectionResult['status'] && !empty($collectionResult['data'])): ?>
        <?php foreach($collectionResult['data'] as $row): ?>
            <option value="<?= $row['collection_id'] ?>"><?= $row['collection_name'] ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
    </select>
    </div>

    <div class="form-group">
      <label>Metal</label>
      <select name="metals_id" required>
        <option value="">-- Select Metal --</option>
        <?php if ($metalResult['status'] && !empty($metalResult['data'])): ?>
        <?php foreach($metalResult['data'] as $row): ?>
            <option value="<?= $row['id'] ?>"><?= $row['metal_name'] ?></option>
        <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Diamond</label>
      <select name="diamonds_id" required>
        <option value="">-- Select Diamond --</option>
        <?php if ($diamondResult['status'] && !empty($diamondResult['data'])): ?>
        <?php foreach($diamondResult['data'] as $row): ?>
            <option value="<?= $row['id'] ?>"><?= $row['diamonds_type'] ?></option>
        <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group">
      <label>No. of Stones</label>
      <input type="number" name="of_stones">
    </div>

    <div class="form-group">
      <label>Diamond Weight</label>
      <input type="number" step="0.01" name="diamond_weight">
    </div>

    <div class="form-group">
      <label>Product Images</label>
      <input type="file" name="product_image[]" multiple="multiple">
    </div>

    <button type="submit" name="submit">Insert Product</button>
  </form>
</div>
</body>
</html>
