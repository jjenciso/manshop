<?php
include_once("db.php");
include_once("header.php");

$product_type_to_display = 'tops'; // Set the product type you want to display
$items_per_page = 10; // Number of items to display per page

// Get the current page number from the URL, or set it to 1 if not present
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query based on the current page number and the number of items per page
$offset = ($page - 1) * $items_per_page;

// Get the total number of products for the specified product type
$sql = "SELECT COUNT(*) FROM products WHERE product_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_type_to_display);
$stmt->execute();
$total_products = $stmt->get_result()->fetch_row()[0];

// Get the products for the specified product type, with the specified offset and limit
$sql = "SELECT * FROM products WHERE product_type = ? ORDER BY id ASC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $product_type_to_display, $offset, $items_per_page);
$stmt->execute();
$all_products = $stmt->get_result();
?>

<section id="page-header" class="tops-header">
  <h1>#classicSwag</h1>
  <h3>Made by men, worn by men</h3>
</section>

<section id="product1" class="section-p1">
  <div class="pro-container">
    <?php
    while ($row = $all_products->fetch_assoc()) {
    ?>
      <div class="pro" data-product-id="<?php echo $row['id']; ?>" onclick="redirectToProduct(<?php echo $row['id']; ?>)">
        <img src="<?php echo htmlspecialchars('uploads/' . $row['image_path']); ?>" alt="Product Image" />
        <div class="des">
          <span><?php echo htmlspecialchars($row['name']); ?></span>
          <h5><?php echo htmlspecialchars($row['description']); ?></h5>
          <h4>$<?php echo htmlspecialchars($row['price']); ?></h4>
        </div>
        <form action="add_to_cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
          <button class="add-to-cart" type="submit"><i class="fas fa-shopping-cart"></i></button>
        </form>
      </div>
    <?php
    }
    ?>
  </div>
</section>

<?php
// Display the pagination links if there are more than one page of products
if ($total_products > $items_per_page) {
?>
  <section id="pagination" class="section-p1">
    <?php
    // Generate the previous page link if the current page is not the first page
    if ($page > 1) {
    ?>
      <a href="?page=<?php echo $page - 1; ?>">Previous</a>
    <?php
    }

    // Generate the page links
    for ($i = 1; $i <= ceil($total_products / $items_per_page); $i++) {
    ?>
      <a href="?page=<?php echo $i; ?>" <?php echo $page == $i ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
    <?php
    }

    // Generate the next page link if the current page is not the last page
    if ($page < ceil($total_products / $items_per_page)) {
    ?>
      <a href="?page=<?php echo $page + 1; ?>">Next</a>
    <?php
    }
    ?>
  </section>
<?php
}
?>

<?php
include_once("newsletter.php");
include_once("footer.php");
?>