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
        $code = substr(md5(mt_rand()), 10000, 99999);
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "website";
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if ($conn->connect_error) {
            die('Could not connect to database.');
        }
        // $verifystmt = $conn->prepare("INSERT INTO verify (email, password, code) VALUES(?, ?, ?);");
        // $verifystmt->bind_param("sss", $email, $password, $code);
        // if ($verifystmt->execute()) {
        //   $to = $email;
        //   $subject = "Website Activation Code";
        //   $from = 'jameslicarlover@gmail.com';
        //   $body = "Your activation code is '.$code.'. Please click on this link <a href = 'verification.php'Verify.php?email = '.$email.'&code ='.$code.'</a> to activate your account.";
        //   $headers = "From: ".$from;
        //   mail($to, $subject, $body, $headers);
        //   echo "An activation code was sent to your email. Follow the instructions in the email to activate your account.";
        //
        // }
        // else {
        //   echo "Failed";
        // }

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
            $stmt1 = $conn->prepare("INSERT INTO position (email) VALUES(?);")
            $stmt2 = $conn->prepare("INSERT INTO score (email) VALUES(?);")

            $stmt2->bind_param("s", $email);
            $stmt1->bind_param("s", $email);
            if ($stmt->execute() and $stmt1->execute() and $stmt2->execute()) {
                echo "New record inserted sucessfully.";
                $_SESSION['Email'] = $email;
                $_SESSION['loggedin'] = true;
                header("Location: editprofile.php");
            }
            else {
                echo $stmt->error;
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
    else {
        echo "All field are required.";
        die();
    }
}
else {
    echo "Submit button is not set";
}
?>
