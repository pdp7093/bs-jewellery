<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jewellery Product Customization</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f9f9f9;
    }

    .product-container {
      margin-top: 40px;
    }

    .price {
      font-size: 24px;
      font-weight: 700;
      color: #b8860b;
    }

    .option-label {
      font-weight: 600;
      margin-top: 10px;
    }

    .product-img {
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<script type='text/javascript'>
  var myArray=[];
  fetch('http://localhost/Brainstream/Website_task/bs-jewellery/Api/View-Product?id=17')
    .then(response => response.json()) // Parse the response as JSON
    .then(data => {
      console.log(data);
      data.metals.forEach(element => {
        console.log(element);
      });
       myArray = data.product.product_image.split(',');
      console.log(myArray);
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });
</script>

<body>
  <div class="container product-container">
    <div class="row">
      <!-- Left Side (Images) -->
      <div class="col-md-6 text-center">
        <script>
          for (let x of myArray) {
            console.log(x);
          }
        </script>
      </div>

      <!-- Right Side (Details + Customization) -->
      <div class="col-md-6">
        <h2 id="productName">The Channing Bangle</h2>
        <p>Beautifully crafted bangle with customizable options.</p>

        <div class="option-label">Select Metal</div>
        <select id="metalSelect" class="form-select">
          <option value="18k-yellow" data-price="5000">18K Yellow Gold</option>
          <option value="18k-white" data-price="5200">18K White Gold</option>
          <option value="14k-yellow" data-price="4000">14K Yellow Gold</option>
          <option value="14k-white" data-price="4200">14K White Gold</option>
        </select>

        <div class="option-label">Select Diamond Quality</div>
        <select id="diamondSelect" class="form-select">
          <option value="si-ij" data-price="10000">SI IJ - ₹10,000</option>
          <option value="vvs-ef" data-price="20000">VVS EF - ₹20,000</option>
        </select>

        <div class="option-label">Select Size</div>
        <select id="sizeSelect" class="form-select">
          <option value="2.2" data-factor="1">Size 2.2</option>
          <option value="2.4" data-factor="1.1">Size 2.4 (+10% weight)</option>
          <option value="2.6" data-factor="1.2">Size 2.6 (+20% weight)</option>
        </select>

        <hr>
        <div class="price">₹ <span id="finalPrice">0</span></div>

        <button class="btn btn-warning mt-3">Add to Cart</button>
        <button class="btn btn-success mt-3">Buy Now</button>
      </div>
    </div>
  </div>

  <script>
    // Base price from backend (example)
    let basePrice = 15000;

    function calculatePrice() {
      let metal = document.getElementById("metalSelect");
      let diamond = document.getElementById("diamondSelect");
      let size = document.getElementById("sizeSelect");

      let metalPrice = parseFloat(metal.options[metal.selectedIndex].dataset.price);
      let diamondPrice = parseFloat(diamond.options[diamond.selectedIndex].dataset.price);
      let sizeFactor = parseFloat(size.options[size.selectedIndex].dataset.factor);

      let finalPrice = (basePrice + metalPrice + diamondPrice) * sizeFactor;

      document.getElementById("finalPrice").innerText = finalPrice.toLocaleString("en-IN");
    }

    document.getElementById("metalSelect").addEventListener("change", calculatePrice);
    document.getElementById("diamondSelect").addEventListener("change", calculatePrice);
    document.getElementById("sizeSelect").addEventListener("change", calculatePrice);

    // Initial load
    calculatePrice();
  </script>
</body>

</html>