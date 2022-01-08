<?php
session_start();
if (isset($_POST['saveedits'])) {
  $email = $_SESSION['Email'];
  if (isset($_POST['index'])) {
    $index = $_POST['index'];
    echo $index;
  }
  else {
     die('error');
  }

  $sendposition = $_POST['positionrole'.$index];
  $sendcompany = $_POST['companyinfo'.$index];
  $sendtype = $_POST['positiontype'.$index];
  $sendmessage = $_POST['writemessage'.$index];
  $salstart = $_POST['salarystart'.$index];
  $salend = $_POST['salaryend'.$index];
  $currency = $_POST['currency'.$index];
  $wage = $_POST['hourlywage'.$index];
  $remoteornah = $_POST['recremote'.$index];
  $postingid = $_POST['postingid'.$index];
  $jobcity = $_POST['reccity'.$index];
  $jobregion = $_POST['recregion'.$index];
  $jobcountry = $_POST['reccountry'.$index];
  $previouspostingname = $_POST['previouspostingname'.$index];

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
  $savemessage->salarystart = $salstart;
  $savemessage->salaryend = $salend;
  $savemessage->wage = $wage;
  $savemessage->currency = $currency;
  $savemessage->remote = $remoteornah;
  $savemessage->type = $sendtype;
  $savemessage->email = $email;
  $savemessage->id = $postingid;
  $date = date('Y-m-d H:i:s');
  $savemessage->dateposted = $date;

  $date = date('Y-m-d H:i:s');
  $savemessage->dateposted = $date;

  if ($remoteornah = "In Person") {
    $savemessage->city = $jobcity;
    $savemessage->region = $jobregion;
    $savemessage->country = $jobcountry;
  }
  else {
    $savemessage->city = NULL;
    $savemessage->region = NULL;
    $savemessage->country = NULL;
  }
  $messageobject = serialize($savemessage);
  $updated = False;
  $postingnum = "Posting".$index;

  if ($conn->query("UPDATE sent_postings SET `$postingnum` = '$messageobject' WHERE Email = '$email'")) {
    $updated = True;
  }
  else {
    echo "Dammit1";
  }
  if ($updated == "True") {
    header("Location: sentpostings.php?editsuccess=Posting Updated");
  }
  else {
    header("Location: sentpostings.php?editerror=Update Failed");
  }
}
$conn->close();
?>
