<!DOCTYPE html>
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
      <a href="emptysearch.html">Search</a>
      <a href="emptymessages.html">Messages</a>
      <a href="emptyprofile.html">Profile</a>
      <a href="loginpage.php">Login</a>
      <a class="active" href="index.php">Sign Up</a>
    </div>

    <div class="signupcontainer">
      <form action="signup.php" method = "POST">
        <div>
          <table>
            <h1 class = "form__title">Sign Up</h1>
            <tr>
              <td>Email: </td>
              <td>
                <input type = "email" name="Email" placeholder = "Email" required>
              </td>
            </tr>
            <tr>
              <td>First Name: </td>
              <td>
                <input type = "text" name="FirstName" placeholder = "First Name" required>
              </td>
            </tr>
            <tr>
              <td>Last Name: </td>
              <td>
                <input type = "text" name="LastName" placeholder = "Last Name" required>
              </td>
            </tr>
            <tr>
              <td>Password: </td>
              <td>
                <input type = "password" id = "password" name="Password" placeholder = "Password" required>
              </td>
            </tr>
            <tr>
              <td>Repeat Password: </td>
              <td>
                <input type = "password" id = "passwordrepeat" name="PasswordRepeat" placeholder = "Confirm Password" required onkeyup="checkPasswordMatch();">
                <script>
                function checkPasswordMatch() {
                  var password = document.getElementById("password");
                  var confirm = document.getElementById("passwordrepeat");
                  var submit = document.getElementById("submit");
                  if (password.value == confirm.value) {
                    confirm.style.background = "palegreen";
                    password.style.background = "palegreen";
                    submit.style.color = "black";
                    $('input[name="signup"]').prop('disabled', false);
                  }
                  else {
                    confirm.style.background = "#ffcccb";
                    password.style.background = "#ffcccb";
                    submit.style.color = "gray";
                    $('input[name="signup"]').prop('disabled', true);
                  }
                }
                </script>

              </td>

            </tr>
          </table>
          <?php
          if (isset($_GET['signuperror'])) { ?>
            <p class = "error" style = "text-align: center"><?php echo $_GET['signuperror']; ?></p>
          <?php }
          else { ?>
            <div style = "height: 55px">&nbsp;</div>
          <?php }?>
          <div class = "submitbutton" style = "text-align: center">
            <td><input type="submit" value="Submit" name="signup" onclick="submitted()" id = "submit"></td>
          </div>
          <div class = "linkloginbutton" style = "text-align: center">
            <input id = "linklogin" type="button" onclick="location.href='loginpage.php';" value="Login" />
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
