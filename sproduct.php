<?php
include_once("db.php");
include_once("header.php");

// Function to escape and sanitize HTML output
function sanitizeOutput($value)
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Fetch all products
$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$all_products = $stmt->get_result();

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
  $product_id = mysqli_real_escape_string($conn, $_GET['id']);

  // Fetch product data
  $query = "SELECT * FROM products WHERE id = '$product_id'";
  $result = mysqli_query($conn, $query);

  if ($result && $row = mysqli_fetch_assoc($result)) {
    // Found product details
    $product_name = sanitizeOutput($row['name']);
    $product_brand = sanitizeOutput($row['description']);
    $price = sanitizeOutput($row['price']);
    $product_info = sanitizeOutput($row['item_info']);
    $image_path = sanitizeOutput('uploads/' . $row['image_path']);
  } else {
    echo "Product not found.";
    exit();
  }
} else {
  header("Location: index.php");
  exit();
}



?>

<section id="prodetails" class="section-p1">
  <div class="single-pro-image">
    <img src="<?= $image_path; ?>" width="100%" id="MainImg" alt="" />

    <!-- Display small thumbnail images here -->

  </div>
  <div class="single-pro-details">
    <h6><?= $product_brand; ?></h6>
    <h4><?= $product_name; ?></h4>
    <h2>$<?= $price; ?></h2>
    <select>
      <option>Select Size</option>
      <option>XL</option>
      <option>XXL</option>
      <option>Small</option>
      <option>Medium</option>
      <option>Large</option>
    </select>
    <input type="number" value="1" />
    <form action="add_to_cart.php" method="post">
      <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
      <button class="normal" type="submit">Add To Cart</button>
    </form>
    
    <h4>Product Details</h4>
    <span><?= $product_info; ?></span>
  </div>
</section>

<section id="product1" class="section-p1">
  <h2>Exclusive Collections</h2>
  <p>For Every Occasion We Got You Covered</p>

  <div class="pro-container">
    <?php
    // Fetch all products
    $all_products_array = [];

    while ($row = mysqli_fetch_assoc($all_products)) {
      $all_products_array[] = $row;
    }

    // Shuffle all products
    shuffle($all_products_array);

    // Display the first 10 products
    for ($i = 0; $i < min(10, count($all_products_array)); $i++) {
      $row = $all_products_array[$i];
    ?>
      <div class="pro" data-product-id="<?php echo $row['id']; ?>" onclick="redirectToProduct(<?= $row['id']; ?>)">
        <img src="<?= sanitizeOutput('uploads/' . $row['image_path']); ?>" alt="Product Image" />
        <div class="des">
          <span><?= sanitizeOutput($row['name']); ?></span>
          <h5><?= sanitizeOutput($row['description']); ?></h5>
          <h4>$<?= sanitizeOutput($row['price']); ?></h4>
        </div>
        <form action="add_to_cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
          <button class="add-to-cart" type="submit"><i class="fas fa-shopping-cart"></i></button>
        </form>
      </div>
    <?php
    }
    ?>
  </div>

</section>

<?php
include_once("newsletter.php");
include_once("footer.php");
?>