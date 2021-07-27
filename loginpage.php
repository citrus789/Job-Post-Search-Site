<?php
include('login.php');

if(isset($_SESSION["Email"]) && $_SESSION['loggedin'] == true) {
  header("location: editprofile.php");
}
?>

<html>
  <head>
    <meta charset = "UTF-8">
    <meta name = "description" content = "This is my first experimental website">

    <title>First Website</title>
    <link rel = "stylesheet" href = "styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="topnav">
      <a href="search.html">Search</a>
      <a href="emptymessages.html">Messages</a>
      <a href="emptyprofile.php">Profile</a>
      <a class = "active" href="loginpage.php">Login</a>
      <a href="index.html">Sign Up</a>
    </div>


    <div class="logincontainer">

      <form action="login.php" method = "POST">
        <table>
          <h1 class = "form__title">Login</h1>
          <tr>
            <td>Email:</td>
            <td>
              <input type = "email" name="Email" placeholder = "Email" required>
            </td>
          </tr>
          <tr>
            <td>Password:</td>
            <td>
              <input type = "password" name="Password" placeholder = "Password" required>
            </td>
          </tr>
        </table>
        <div>
          <input type="submit" value="Submit" name="submit" id = "submit">
        </div>
        <div>
          <a class = "button" href="index.html" id = "linksignup" >Sign Up</a>
        </div>
      </form>
    </div>


  </body>
</html>
