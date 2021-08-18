<?php
session_start();
if (isset($_POST['sendmessage'])) {
  $email = $_SESSION['Email'];
  $sendposition = $_POST['positionrole'];
  $sendcompany = $_POST['companyinfo'];
  $sendtype = $_POST['positiontype'];
  $sendmessage = $_POST['writemessage'];
  $salstart = $_POST['salarystart'];
  $salend = $_POST['salaryend'];
  $currency = $_POST['currency'];
  $wage = $_POST['hourlywage'];

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
        if ($row['Remote'] == '1') {
          $remoteornah = "Remote";
          $jobcity = NULL;
          $jobregion = NULL;
          $jobcountry = NULL;
        }
        if ($row['Remote'] == '2') {
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
        if ($row['Remote'] == '3') {
          $remoteornah = "In Person";
          if (!is_null($row['City']) and !is_null($row['Region']) and !is_null($row['Country'])) {
            $jobcity = $row['City'];
            $jobregion = $row['Region'];
            $jobcountry = $row['Country'];
          }
          else if (!is_null($row['City']) and !is_null($row['Country'])) {
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
  $savemessage->wage = $wage;
  $savemessage->currency = $currency;
  $savemessage->remote = $remoteornah;
  $savemessage->type = $sendtype;
  $savemessage->email = $email;

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
  $select = "SELECT * FROM sent_postings WHERE Email = '$email'";
  $exists = $conn->query($select);
  if ($exists->num_rows == 0) {
    $insert = $conn->prepare("INSERT INTO sent_postings (email) VALUES (?)");
    $insert->bind_param("s", $email);
    if ($insert->execute()) {
      echo "Email added";
    }
  }
  $postings = $conn->query("SELECT Posting1, Posting2, Posting3, Posting4, Posting5, Posting6, Posting7 FROM sent_postings WHERE Email = '$email'");
  $row = $postings -> fetch_array(MYSQLI_NUM);
  $postingerror = "True";

  function formatString($var) {
    return (strtolower(preg_replace('/\s+/', '', $var)));
  }

  for ($i = 1; $i <= 7; $i++) {
    $column = "Posting".$i;
    if ($row[$i] != "NULL") {
      if (formatString(unserialize($row[$i])->position) == formatString($sendposition) and formatString(unserialize($row[$i])->company) == formatString($sendcompany)) {
        $conn->query("UPDATE sent_postings SET `$column` = '$messageobject' WHERE Email = '$email'");
        $postingerror = "False";
        break;
      }
    }

    if (empty($row[$i - 1]) or is_null($row[$i - 1]) or $row[$i - 1] == "NULL") {
      $conn->query("UPDATE sent_postings SET `$column` = '$messageobject' WHERE Email = '$email'");
      $postingerror = "False";
      break;
    }
    else {
      $postingerror = "True";
    }
  }
  if ($postingerror == "False") {
    $updated = True;
    if (!empty($_POST['send'])) {
      $checked = $_POST['send'];
    }
  }
  else {
    header("Location: searchresults.php?senderror=Posting Limit Reached");
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
$insert->close();
?>
