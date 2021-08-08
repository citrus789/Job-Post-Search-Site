<?php
session_start();
if (isset($_POST['sendmessage'])) {
  $email = $_SESSION['Email'];
  $sendposition = $_POST['positionrole'];
  $sendcompany = $_POST['positioncompany'];
  $sendmessage = $_POST['writemessage'];
  $salstart = $_POST['salarystart'];
  $salend = $_POST['salaryend'];
  $currency = $_POST['currency'];


  $host = "localhost";
  $dbUsername = "root";
  $dbPassword = "";
  $dbName = "website";
  $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
  if ($conn->connect_error) {
      die('Could not connect to database.');
  }

  $postinginfo = "SELECT * FROM position WHERE Email = '$email'";
  if ($select = $conn->query($postinginfo)) {
    if ($row = $select -> fetch_array(MYSQLI_ASSOC)) {
      if (!is_null($row['Remote'])) {
        if ($row['Remote'] == '0') {
          $remoteornah = "Remote";
          $jobcity = NULL;
          $jobregion = NULL;
          $jobcountry = NULL;
        }
        if ($row['Remote'] == '1') {
          $remoteornah = "Temporarily Remote";
          if (!is_null($row['City']) and !is_null($row['Region']) and !is_null($row['Country'])) {
            $jobcity = $row['City'];
            $jobregion = $row['Region'];
            $jobcountry = $row['Country'];
          }
          if (!is_null($row['City']) and !is_null($row['Country'])) {
            $jobcity = $row['City'];
            $jobregion = NULL;
            $jobcountry = $row['Country'];
          }
          else {
            $jobcity = NULL;
            $jobregion = NULL;
            $jobcountry = NULL;
          }
        }
        if ($row['Remote'] == '2') {
          $remoteornah = "In Person";
          if (!is_null($row['City']) and !is_null($row['Region']) and !is_null($row['Country'])) {
            $jobcity = $row['City'];
            $jobregion = $row['Region'];
            $jobcountry = $row['Country'];
          }
          if (!is_null($row['City']) and !is_null($row['Country'])) {
            $jobcity = $row['City'];
            $jobregion = NULL;
            $jobcountry = $row['Country'];
          }
          else {
            $jobcity = NULL;
            $jobregion = NULL;
            $jobcountry = NULL;
          }
        }
      }
      else {
        $recremote = NULL;
      }
    }
  }
  $savemessage = new stdClass();
  $savemessage->position = $sendposition;
  $savemessage->company = $sendcompany;
  $savemessage->message = $sendmessage;
  $savemessage->salarystart = $salstart;
  $savemessage->salaryend = $salend;
  $savemessage->currency = $currency;
  $savemessage->remote = $remoteornah;
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

  $update = "UPDATE position SET SentMessage = '$messageobject' WHERE Email = '$email'";
  if ($conn->query($update)) {
    $updated = True;
  }
  if (!empty($_POST['send'])) {
    $checked = $_POST['send'];
  }
  else {
    header("Location: searchresults.php?senderror=No Users Selected");
    exit();
  }
  // echo $checked[0];
  $sendtousers = array();
  foreach ($checked as $useremail) {
    // echo $useremail;
    if (isset($checked)) {
      array_push($sendtousers, $useremail);
      // echo $useremail;
    }
    else {
      array_push($sendtousers, NULL);
    }
  }
  $postingname = $sendposition . $sendcompany;
  // echo $messageobject;

  // echo $email;
  $column = mysqli_query($conn, "SHOW COLUMNS FROM `position` LIKE '$postingname'");
  $exists = (mysqli_num_rows($column))?TRUE:FALSE;
  //echo $exists ? 'true' : 'false';
  if (!$exists) {
    // echo "okay, doesn't exist";
    $alter = "ALTER TABLE `position` ADD `$postingname` BLOB DEFAULT NULL";
    // echo $conn->errno;
    if ($conn->query($alter)) {
      $exists = true;
      // echo "executed";
    }
    // echo "prepared";
  }
  foreach ($sendtousers as $useremail) {
    if ($useremail != NULL) {
      // echo $useremail;
      if ($exists) {
        $send = "UPDATE position SET `$postingname` = '$messageobject' WHERE Email = '$useremail'";
        // echo "It exists at least";
        // echo $conn->errno;
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
    header("Location: messages.php");
  }
  else {
    header("Location: searchresults.php?senderror=Could Not Send Posting");
    exit();
  }
}
$conn->close();
?>
