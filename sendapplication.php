<?php
session_start();
if (isset($_POST['sendapplication'.$_SESSION['index']])) {
  $email = $_SESSION['Email'];
  $username = $_SESSION['Email'];
  $index = $_SESSION['index'];

  $host = "localhost";
  $dbUsername = "root";
  $dbPassword = "";
  $dbName = "website";
  $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
  if ($conn->connect_error) {
      die('Could not connect to database');
  }
  $sentapplications = "SELECT * FROM sent_application";

  $result = $conn->query($sentapplications);
  $exists = false;
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($row['Email'] == $email) {
      $exists = true;
      echo "Email already exists";
      break;
    }
  }
  if ($exists == "false") {
    echo "doesn't exist email";
    $insert = $conn->prepare("INSERT INTO sent_application (Email) VALUES (?)");
    $insert->bind_param("s", $username);
    if ($insert->execute()) {
      echo "Email added";
    }
  }


  $cvtext = $_POST['cvtext'.$index];
  $weblink = $_POST['weblink'.$index];
  $transcript = $_FILES['transcript'.$index]['name'];
  $cvfile = $_FILES['cv'.$index]['name'];
  $resumefile = $_FILES['resume'.$index]['name'];
  $resumetype = strtolower(pathinfo($resumefile, PATHINFO_EXTENSION));
  $cvtype = strtolower(pathinfo($cvfile, PATHINFO_EXTENSION));
  $transcripttype = strtolower(pathinfo($transcript, PATHINFO_EXTENSION));
  $uploaded = false;
  // valid file extensions
  $extensions = array("doc","docx","pdf","txt", "html");

  function clean($string) {
    $string = str_replace(' ', '-', $string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
  }
  $sql = "SELECT * FROM position";
  if ($result = $conn->query($sql)) {
    $numcolumns = $result->field_count;
  }

  $postinglist = array();
  $postingcount = 0;
  while($row = $result -> fetch_array(MYSQLI_NUM)) {
    if($row[0] == $username) {
      for ($i = 26; $i < $numcolumns; $i++) {
        if ($row[$i] != "NULL" and !is_null($row[$i])) {
          array_push($postinglist, $row[$i]);
          $postingcount++;
        }
      }
    }
  }

  $application = new stdClass();
  $sendtoname = unserialize($postinglist[$index])->position." ".unserialize($postinglist[$index])->company." ".unserialize($postinglist[$index])->id;
  $application->id = unserialize($postinglist[$index])->id;
  // $application->id = unserialize($postinglist[$index])->id;
  // Check extension
  if(!empty($resumefile) and in_array($resumetype, $extensions)) {
    //resume time!
    $temp = explode(".", $resumefile);
    $newfilename = "resume ".$email." - ".unserialize($postinglist[$index])->position. ' '.unserialize($postinglist[$index])->id. '.' . end($temp);
    // echo $newfilename;
    if (!file_exists('resume/'.$email)) {
      mkdir('resume/'.$email, 0777, true);
      echo "made dir";
    }


    if (!file_exists('resume/'.$email.'/'.$newfilename)) {
      if (move_uploaded_file($_FILES["resume".$index]["tmp_name"],'resume/'.$email.'/'.$newfilename)) {
        $uploaded = true;
        $application->resume = 'resume/'.$email.'/'.$newfilename;
      }
      else {
        $uploaded = false;
        echo 'Error in uploading file - '.$_FILES['resume'.$index]['name'].'';
      }
    }
  }
  else {
    echo "used applicant email as resume";
    $application->resume = $email;
    $uploaded = true;
  }

  if (!empty($cvfile) and in_array($cvtype, $extensions) and $uploaded == "true") {
    //cv time!
    $temp = explode(".", $cvfile);
    $newfilename = "cv ".$email." - ".unserialize($postinglist[$index])->position. ' '.unserialize($postinglist[$index])->id. '.' . end($temp);
    // echo $newfilename;
    // chdir("..");
    if (!file_exists('coverletter/'.$email)) {
      mkdir('coverletter/'.$email, 0777, true);
    }
    if (move_uploaded_file($_FILES["cv".$index]["tmp_name"],'coverletter/'.$email.'/'.$newfilename)) {
      $application->cv = 'coverletter/'.$email.'/'.$newfilename;
      $uploaded = true;
    }
    else {
      $uploaded = false;
      echo 'Error in uploading file - '.$_FILES['coverletter'.$index]['name'].'';
    }
  }
  else {
    echo "cv textarea";
    $application->cv = $cvtext;
    $uploaded = true;
  }
  if (!empty($transcript) and in_array($transcripttype, $extensions) and $uploaded == "true") {
    //cv time!
    $temp = explode(".", $transcript);
    $newfilename = "file ".$email." - ".unserialize($postinglist[$index])->position. ' '.unserialize($postinglist[$index])->id. '.' . end($temp);
    // echo $newfilename;
    // chdir("..");
    if (!file_exists('otherfile/'.$email)) {
      mkdir('otherfile/'.$email, 0777, true);
    }
    if (move_uploaded_file($_FILES["transcript".$index]["tmp_name"],'otherfile/'.$email.'/'.$newfilename)) {
      $application->otherfile = 'otherfile/'.$email.'/'.$newfilename;
      $uploaded = true;
    }
    else {
      $uploaded = false;
      echo 'Error in uploading file - '.$_FILES['transcript'.$index]['name'].'';
    }
  }
  else {
    $application->otherfile = "NULL";
    $uploaded = true;
  }

  if (isset($weblink)) {
    $application->weblink = $weblink;
  }

  $column = mysqli_query($conn, "SHOW COLUMNS FROM `sent_application` LIKE '$sendtoname'");
  $exists = (mysqli_num_rows($column))?TRUE:FALSE;

  if (!$exists) {
    $alter = "ALTER TABLE `sent_application` ADD `$sendtoname` BLOB DEFAULT NULL";
    echo $conn->errno;
    if ($conn->query($alter)) {
      $exists = true;
    }
    // echo "prepared";
  }
  $application = serialize($application);
  $insert = "UPDATE sent_application SET `$sendtoname` = '$application' WHERE Email = '$email'";

  if($conn->query($insert)) {
    echo 'Data inserted successfully';
    header("Location: sentapplications.php");
  }
  else {
    echo 'Error: '.mysqli_error($conn);
  }
  $conn->close();
}
 ?>
