<?php
session_start();
include_once("header.php");
?>

<?php // Don't forget to start the session
// Include the database configuration file
include_once("db.php");

// ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Query the database to get the user data
  $stmt = $conn->prepare("SELECT user_id, password FROM user_info WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  // Check if the user exists and the password is correct
  if ($user && password_verify($password, $user["password"])) {
    // Set the username in the session
    $_SESSION["user"] = $username;

    // check if the user is the admin
    if ($username === "shopAdmin") {
      header("Location: admin/admin.php");
      exit;
    }

    // Load the user's cart data from the database
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      // Assuming you have product details in another table 'products', adjust the query accordingly
      $product_stmt = $conn->prepare("SELECT description, price, image_path FROM products WHERE id = ?");
      $product_stmt->bind_param("i", $row["product_id"]);
      $product_stmt->execute();
      $product_result = $product_stmt->get_result();
      $product = $product_result->fetch_assoc();

      $_SESSION['cart'][$row["product_id"]] = [
        'description' => $product["description"],
        'price' => $product["price"],
        'image_path' => $product["image_path"],
        'quantity' => $row["quantity"],
        'subtotal' => $row["quantity"] * $product["price"]
      ];
    }

    // Redirect to the home page
    header("Location: tops.php");
    exit;
  } else {
    if ($user) {
      $error_message = "Incorrect password";
    } else {
      $error_message = "User not found <br> Sign up for an account for free";
    }
  }
}

$conn->close();

// ...

?>

<section id="log-in" class="section-m1 section-p1">
  <form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required />

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required />

    <div class="btn-container">
      <button class="normal" type="submit">Login</button>
      <button class="normal"><a href="signup.php">Sign Up</a></button>
    </div>
  </form>
  <?php
  if (isset($error_message)) {
    echo "<p>$error_message</p>";
  }
  ?>
</section>

<?php
include_once("footer.php");
?>