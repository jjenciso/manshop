<?php
session_start();
include_once("../db.php");

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
  $loggedIn = false;
} else {
  $loggedIn = true;
  $username = $_SESSION['user'];
}

// Handle logout
if (isset($_POST['logout'])) {
  // Destroy the session and redirect to the login page
  session_destroy();
  header("Location: ../index.php");
  exit;
}

// Check if the form has been submitted
if (isset($_POST["submit"])) {
  // Get the form data
  $productbrand = $_POST["productbrand"];
  $productname = $_POST["productname"];
  $producttype = $_POST["type"];
  $iteminfo = $_POST["info"];
  $price = $_POST["price"];
  $discount = $_POST["discount"];

  // Check if a file has been uploaded
  if (isset($_FILES["imageUpload"]) && $_FILES["imageUpload"]["error"] == UPLOAD_ERR_OK) {
    // Get the file information
    $upload_dir = "../uploads/";
    $product_image = $upload_dir . basename($_FILES["imageUpload"]["name"]);
    $imageType = strtolower(pathinfo($product_image, PATHINFO_EXTENSION));

    // Check if the file is an image
    if ($imageType != 'jpg' && $imageType != 'png' && $imageType != 'jpeg' && $imageType != 'webp') {
      echo "<script>alert('Invalid image format')</script>";
      exit;
    }

    // Check if the file already exists
    if (file_exists($product_image)) {
      echo "<script>alert('The file already exists')</script>";
      exit;
    }

    // Check if the file size is 0
    if ($_FILES["imageUpload"]["size"] === 0) {
      echo "<script>alert('The photo size is 0. Please change the photo')</script>";
      exit;
    }

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $product_image)) {
      echo "<script>alert('There was a problem uploading the image')</script>";
      exit;
    }

    // Insert the new product into the database
    $sql = "INSERT INTO products(name, description, price, discount, product_type, item_info, image_path) VALUES(?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssss", $productbrand, $productname, $price, $discount, $producttype, $iteminfo, $product_image);

    if ($stmt->execute()) {
      echo "Product added succesfully";
    } else {
      echo "<script>alert('There was a problem uploading the item')</script>";
    }

    $stmt->close();
  } else {
    echo "<script>alert('Please select an image to upload')</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="../styles.css" />
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <header id="header">
    <h1 class="logo"><a href="index.html">MAN</a></h1>

    <div>
      <ul id="navbar">
        <?php if ($loggedIn) { ?>

          <p id="welcome">Hello, <?php echo $username; ?></p>
          <form action="" method="post">
            <li id="lg-user">
              <button type="submit" name="logout"><i class="fa-solid fa-right-from-bracket"></i></button>
            </li>
          </form>

        <?php } else { ?>

          <li id="lg-user">
            <a href="login.php"><i class="fa-solid fa-user"></i></a>
          </li>

        <?php } ?>
      </ul>
    </div>
  </header>

  <section id="admin" class="section-m1 section-p1">
    <form style="height: auto;" action="" method="post" enctype="multipart/form-data">
      <input type="text" name="productbrand" id="productbrand" placeholder="Product Brand .." required />
      <input type="text" name="productname" id="productname" placeholder="Product Name .." required />
      <input type="text" name="type" id="type" placeholder="Item Type" required />
      <textarea style="height: 200px; width: 100%; padding: 5px; margin-bottom: 16px;" name="info" id="itemInfo" cols="30" rows="10" placeholder="Item Info" required></textarea>
      <input type="number" name="price" id="price" placeholder="Product Price.." required />
      <input type="number" name="discount" id="discount" placeholder="Discounted Price.." />
      <input type="file" name="imageUpload" id="imageUpload" required />
      <input class="normal" type="submit" value="Upload" name="submit" />
    </form>
  </section>

  <section id="myproducts">
    <h1>My Products</h1>
    <?php
    $product_type = isset($_GET['type']) ? $_GET['type'] : 'all';
    $sql = "SELECT * FROM products WHERE product_type = ? OR product_type = 'all'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_type);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $product_image = $row['image_path'];
      $product_name = $row['name'];
      $product_price = $row['price'];
      $product_discount = $row['discount'];
      $product_type = $row['product_type'];
      $item_info = $row['item_info'];
      echo "<div class='product'>" .
        "<img src='$product_image' alt='$product_name'>" .
        "<h2>$product_name</h2>" .
        "<p>Price: $$product_price</p>";
      if ($product_discount != 0) {
        echo "<p>Discounted Price: $$product_discount</p>";
      }
      echo "<p>Type: $product_type</p>" .
        "<p>Info: $item_info</p>" .
        "</div>";
    }
    $stmt->close();
    ?>

    <ul id="navbar">
      <li><a href="admin.php">All Products</a></li>
      <li><a href="admin.php?type=tops">Tops</a></li>
      <li><a href="admin.php?type=jeans">Jeans</a></li>
      <li><a href="admin.php?type=shoes">Shoes</a></li>
      <li><a href="admin.php?type=accessories">Accessories</a></li>
      <li><a href="admin.php?type=activewear">Active Wear</a></li>
      <li><a href="admin.php?type=sale">Sale</a></li>
    </ul>

  </section>

</body>

</html>