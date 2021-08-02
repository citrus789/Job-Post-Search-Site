<?php
session_start();
if (isset($_POST['sendmessage'])) {
  $email = $_SESSION['Email'];
  $sendposition = $_POST['positionrole'];
  $sendcompany = $_POST['positioncompany'];
  $sendmessage = $_POST['writemessage'];
  $numuser = $_POST['selectnumber'];

  $host = "localhost";
  $dbUsername = "root";
  $dbPassword = "";
  $dbName = "website";
  $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
  if ($conn->connect_error) {
      die('Could not connect to database.');
  }
  $savemessage = new stdClass();
  $savemessage->position = $sendposition;
  $savemessage->company = $sendcompany;
  $savemessage->message = $sendmessage;
  $savemessage->number = $numuser;
  $messageobject = serialize($savemessage);
  $updated = False;

  $update = "UPDATE position SET SentMessage = '$messageobject' WHERE Email = '$email'";
  if ($conn->query($update)) {
    $updated = True;
  }
  $checked = $_POST['send'];
  echo $checked[0];
  $sendtousers = array();
  foreach ($checked as $useremail) {
    echo $useremail;
    if (isset($checked)) {
      array_push($sendtousers, $useremail);
      echo $useremail;
    }
    else {
      array_push($sendtousers, NULL);
    }
  }
  $postingname = $sendposition . $sendcompany;
  echo $messageobject;
  // $column = $conn->query("SHOW COLUMNS FROM `position` LIKE `$email`");
  // $exists = (mysqli_num_rows($column))?TRUE:FALSE;
  //
  echo $email;
  // $exists = $conn->query("SELECT `$email` FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=[`$dbName`] AND TABLE_NAME=[position]");
  $column = mysqli_query($conn, "SHOW COLUMNS FROM `position` LIKE '$postingname'");
  $exists = (mysqli_num_rows($column))?TRUE:FALSE;
  //echo $exists ? 'true' : 'false';
  if (!$exists) {
    echo "okay, doesn't exist";
    $alter = "ALTER TABLE `position` ADD `$postingname` BLOB DEFAULT NULL";
    echo $conn->errno;
    if ($conn->query($alter)) {
      $exists = true;
      echo "executed";
    }
    echo "prepared";
  }
  foreach ($sendtousers as $useremail) {
    if ($useremail != NULL) {
      echo $useremail;
      if ($exists) {
        $send = "UPDATE position SET `$postingname` = '$messageobject' WHERE Email = '$useremail'";
        echo "It exists at least";
        echo $conn->errno;
        if ($conn->query($send)) {
          $updated = True;
          echo "Updated";
        }
        else {
          $updated = False;
          echo "Could not update";
        }
      }

    }
  }
  if ($updated) {
    echo "Success";
    //header("Location: searchresults.php");
  }
}
?>
