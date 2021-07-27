<?php
session_start(); // Starting Session
$error = ''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
  if (empty($_POST['Email']) || empty($_POST['Password'])) {
    $error = "Email or Password is invalid";
  }
  else{
  // Define $username and $password
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    // mysqli_connect() function opens a new connection to the MySQL server.
    $conn = mysqli_connect("localhost", "root", "", "website");
    // SQL query to fetch information of registerd users and finds user match.
    $query = "SELECT email, password from user_login where email=? AND password=? LIMIT 1";
    // To protect MySQL injection for Security purpose
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->bind_result($email, $password);
    $stmt->store_result();

    if($stmt->fetch()) {//fetching the contents of the row {
      $_SESSION['Email'] = $email;
      $_SESSION['loggedin'] = true;// Initializing Session
      header("location: editprofile.php"); // Redirecting To Profile Page
    }
    else {
      echo "Incorrect Email / Password";
      header("location: loginpage.php");

    }
  }
mysqli_close($conn); // Closing Connection
}
?>
