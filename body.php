<?php
include_once("db.php");


$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$all_products = $stmt->get_result();
?>

<section id="hero">
    <h4>Stand out from the rest</h4>
    <h2>Super value deals</h2>
    <h1>On all products</h1>
    <p>Save more with coupons & up to <span>70% off!</span></p>
    <button>Shop Now</button>
</section>

<section id="product1" class="section-p1">
    <h2>Featured Products</h2>
    <p><span>70% off</span> On Selected Items</p>
    <div class="pro-container">
        <?php
        $random_products = [];

        while (count($random_products) < 10 && $row = mysqli_fetch_assoc($all_products)) {
            $random_products[] = $row;
        }

        shuffle($random_products);

        foreach ($random_products as $row) {

        ?>
            <div class="pro" data-product-id="<?php echo $row['id']; ?>" onclick="redirectToProduct(<?php echo $row['id']; ?>)">
                <img src="<?php echo htmlspecialchars('uploads/' . $row['image_path']); ?>" alt="Product Image" />
                <div class="des">
                    <span><?php echo htmlspecialchars($row['name']); ?></span>
                    <h5><?php echo htmlspecialchars($row['description']); ?></h5>
                    <h4>$<?php echo htmlspecialchars($row['price']); ?></h4>
                </div>
                <form action="add_to_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>" />
                    <button class="add-to-cart" type="submit"><i class="fas fa-shopping-cart"></i></button>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</section>

<section id="banner" class="section-m1">
    <h4>Awesome Discount</h4>
    <h2>Up to <span>70% Off</span> - On Selected Products & Accessories</h2>
    <button class="normal">Explore More</button>
</section>

<section id="product1" class="section-p1">
    <h2>Exclusive Collections</h2>
    <p>For Every Ocassions We Got You Covered</p>

    <div class="pro-container">
        <?php
        $random_products = [];

        while (count($random_products) < 10 && $row = mysqli_fetch_assoc($all_products)) {
            $random_products[] = $row;
        }

        shuffle($random_products);

        foreach ($random_products as $row) {

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
