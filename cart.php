<?php
session_start();
include_once("db.php");
include_once("header.php");
include_once("add_to_cart.php");

// Include add_to_cart.php for handling form submissions
// include_once("add_to_cart.php");

// Sample cart data (replace this with your actual cart data)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

function calculateCartSubtotal($cart)
{
    $subtotal = 0;

    foreach ($cart as $item) {
        // Use the existing subtotal if it exists, otherwise calculate it
        $subtotal += isset($item['subtotal']) ? $item['subtotal'] : $item['price'] * $item['quantity'];
    }

    return $subtotal;
}

// Handle removing an item from the cart
if (isset($_GET['remove_item']) && isset($_SESSION['cart'][$_GET['remove_item']])) {
    unset($_SESSION['cart'][$_GET['remove_item']]);
    session_write_close();
    header('Location: cart.php');
    exit;
}

// Handle updating the quantity of an item in the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cart_index']) && isset($_POST['quantity'])) {
    $cart_index = $_POST['cart_index'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$cart_index])) {
        $_SESSION['cart'][$cart_index]['quantity'] = $quantity;
        $_SESSION['cart'][$cart_index]['subtotal'] = $_SESSION['cart'][$cart_index]['price'] * $quantity;
        session_write_close();
    }

    header('Location: cart.php');
    exit;
}
?>

<section id="cart" class="section-p1">
    <table width="100%">
        <thead>
            <tr>
                <td>Remove</td>
                <td>Image</td>
                <td>Product</td>
                <td>Price</td>
                <td>Quantity</td>
                <td>Subtotal</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $cart_index => $item) : ?>
                <tr>
                    <td><a href="?remove_item=<?php echo htmlspecialchars($cart_index); ?>"><i class="far fa-times-circle"></i></a></td>
                    <td><img src="<?php echo htmlspecialchars('uploads/' . $item['image_path']); ?>" alt="Product Image"></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="cart_index" value="<?php echo htmlspecialchars($cart_index); ?>">
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td>$<?php echo htmlspecialchars($item['subtotal']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section id="cart-add" class="section-p1">
    <div id="coupon">
        <h3>Apply Coupon</h3>
        <div>
            <input type="text" placeholder="Enter Your Coupon">
            <button class="normal" name="apply_coupon">Apply</button>
        </div>
    </div>
    <div id="subtotal">
        <h3>Cart Totals</h3>
        <table>
            <tr>
                <td>Cart Subtotal</td>
                <td>$<?php echo calculateCartSubtotal($cart); ?></td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td>Free</td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>$<?php echo calculateCartSubtotal($cart); ?></strong></td>
            </tr>
        </table>
        <!-- Form to add product to cart -->
        <form action="checkout.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
            <button class="normal" type="submit">Checkout</button>
        </form>
    </div>
</section>

<?php
include_once("footer.php");
ob_end_flush();
?>