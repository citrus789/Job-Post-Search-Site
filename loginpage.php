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
    <div class="bottomnav">
      <div class = "bottomnavcontents">
        <a class="active" href="loginpage.php"><div id = "loginbutton" class = "active">Login</div></a>
        <a href="index.php"><div id = "signupbutton">Sign Up</div></a>
      </div>
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
        <?php if (isset($_GET['loginerror'])) { ?>
          <p class = "error" style = "text-align: center"><?php echo $_GET['loginerror']; ?></p>
        <?php }
        else { ?>
          <div style = "height: 55px">&nbsp;</div>
        <?php }?>
        <div style = "text-align: center">
          <input type="submit" value="Submit" name="submit" id = "submit">
        </div>
        <div style = "text-align: center">
          <input id = "linklogin" type="button" onclick="location.href='index.html';" value="Sign Up" />
        </div>
      </form>
    </div>


  </body>
</html>
