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

  $update = "UPDATE position SET SentMessage = '$messageobject' WHERE Email = $email";
  if ($conn->query($update)) {
    $updated = True;
  }
  
?>
