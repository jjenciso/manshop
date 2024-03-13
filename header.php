<?php
include_once("check_login.php");

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    $loggedIn = true;
    $username = $_SESSION['user'];
} else {
    $loggedIn = false;
}

// Handle logout
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Based</title>
    <!-- CSS -->
    <link rel="stylesheet" href="styles.css" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header id="header">
        <h1 class="logo"><a href="index.php">MAN</a></h1>

        <div>
            <ul id="navbar">
                <li><a class="page" href="activewear.php">active wear</a></li>
                <li><a class="page" href="tops.php">tops</a></li>
                <li><a class="page" href="bottoms.php">jeans</a></li>
                <li><a class="page" href="shoes.php">shoes</a></li>
                <li><a class="page" href="accessories.php">accessories</a></li>
                <li id="lg-bag">
                    <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
                </li>
                <?php if ($loggedIn) { ?>

                    <p style="color: #fff;" id="welcome">Welcome, <?php echo $username; ?></p>
                    <form action="" method="post">
                        <li id="lg-user">
                            <button style="cursor: pointer;" type="submit" name="logout" ><i class="fa-solid fa-right-from-bracket"></i></button>
                        </li>
                    </form>

                <?php } else { ?>

                    <li id="lg-user">
                        <a href="login.php"><i class="fa-solid fa-user"></i></a>
                    </li>

                <?php } ?>

                <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="login.php"><i class="fa-solid fa-user"></i></a>
            <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a>
            <i id="bar" class="fa-solid fa-bars"></i>
        </div>
    </header>