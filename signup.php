<?php
include_once("header.php");
include_once("db.php");

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $email = $_POST["email"];
  $address = $_POST["address"];

  // Prepare and bind
  $stmt = $conn->prepare("SELECT * FROM user_info WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $error_message = "Username already taken. Please choose a different one.";
  } else if ($username == 'admin') {
    $error_message = "Username 'admin' is restricted, Please choose a different one.";
  } else {
    // Insert a new user to the database
    $stmt = $conn->prepare("INSERT INTO user_info (username, password, first_name, last_name, email_address, address) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $username, $password, $firstName, $lastName, $email, $address);

    if ($stmt->execute()) {
      // Succesful signup
      echo "Account Registered Succesfully";
      header("Location: login.php");
      exit();
    } else {
      $error_message = "Error: " . $stmt->error;
    }
  }

  $stmt->close();
}
?>



<section id="sign-up" class="section-p1 section-m1">
  <form action="signup.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required />

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required />

    <label for="fullName">First Name:</label>
    <input type="text" id="firstName" name="firstName" required />

    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required />

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required />


    <div class="btn-container">
      <button class="normal" type="submit">Sign Up</button>
      <button class="normal" type="submit"><a href="login.php">Log In</a></button>
    </div>
  </form>

  <?php
  if (!empty($error_message)) {
    echo "<p>$error_message</p>";
  }
  ?>
</section>



<?php
include_once("footer.php");
?>