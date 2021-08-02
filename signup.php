<?php
session_start();
if (isset($_POST['signup'])) {
    if (isset($_POST['Email']) && isset($_POST['FirstName']) &&
        isset($_POST['LastName']) && isset($_POST['Password'])) {

        $email = $_POST['Email'];
        $firstname = $_POST['FirstName'];
        $lastname = $_POST['LastName'];
        $password = $_POST['Password'];
        $passwordrepeat = $_POST['PasswordRepeat'];
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "website";
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if ($conn->connect_error) {
            die('Could not connect to database.');
        }
        if ($stmt = $conn->prepare("INSERT INTO position (email) VALUES(?);") and $stmt2 = $conn->prepare("INSERT INTO score (email) VALUES(?);")) {

            $stmt2->bind_param("s", $email);
            $stmt->bind_param("s", $email);
            if ($stmt->execute() and $stmt2->execute()) {
              $Select = "SELECT email FROM user_login WHERE email = ? LIMIT 1";
              $Insert = "INSERT INTO user_login(email, firstname, lastname, password) values(?, ?, ?, ?)";

              $stmt = $conn->prepare($Select);
              $stmt->bind_param("s", $email);
              $stmt->execute();
              $stmt->bind_result($email);
              $stmt->store_result();
              $stmt->fetch();
              $rnum = $stmt->num_rows;
              if ($rnum == 0) {
                  $stmt->close();
                  $stmt = $conn->prepare($Insert);
                  $stmt->bind_param("ssss", $email, $firstname, $lastname, $password);
                  if ($stmt->execute()) {
                      echo "New record inserted sucessfully.";
                      $_SESSION['Email'] = $email;
                      $_SESSION['loggedin'] = true;
                      header("Location: editprofile.php");
                  }
                  else {
                      echo $stmt->error;
                  }
               }
            }
            else {
                echo "Email in Use";
                header("Location: index.php?signuperror=Error: Email In Use");
                exit();
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "All field are required.";
        die();
    }
}
else {
    echo "Submit button is not set";
}
?>
