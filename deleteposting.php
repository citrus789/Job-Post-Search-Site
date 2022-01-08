<?php
session_start();
if (isset($_POST['deleteposting'])) {
  $email = $_SESSION['Email'];
  if (isset($_POST['index'])) {
    $index = $_POST['index'];
    echo $index;
  }
  else {
     die('error');
  }


  $previouspostingname = $_POST['previouspostingname'.$index];
  $postingid = $_POST['postingid'.$index];
  $host = "localhost";
  $dbUsername = "root";
  $dbPassword = "";
  $dbName = "website";
  $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
  if ($conn->connect_error) {
      die('Could not connect to database.');
  }


  $updated = False;
  $postingnum = "Posting".$index;

  if ($conn->query("UPDATE sent_postings SET `$postingnum` = NULL WHERE Email = '$email'")) {
    $updated = True;
    if ($conn->query("ALTER TABLE position DROP COLUMN `$postingid`")) {
      $updated = True;
      if (is_null($conn->query("ALTER TABLE sent_application DROP COLUMN `$postingid`")) or $conn->query("ALTER TABLE sent_application DROP COLUMN `$postingid`")) {
        $updated = True;
      }
    }
  }
  else {
    $updated = False;
  }
  if ($updated == "True") {
    header("Location: sentpostings.php?editsuccess=Posting Deleted");
  }
  else {
    header("Location: sentpostings.php?editerror=Delete Failed");
  }
}
$conn->close();
?>
