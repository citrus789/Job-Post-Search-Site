<?php
session_start();
if (isset($_POST['search'])) {
  $email = $_SESSION['Email'];

  $recposition = $_POST["recposition"];
  $rectype = $_POST["rectype"];
  $recskill1 = $_POST["recskill1"];
  $recskill2 = $_POST["recskill2"];
  $recskill3 = $_POST["recskill3"];
  $recskill4 = $_POST["recskill4"];
  $recskill5 = $_POST["recskill5"];
  $recdate = $_POST["recdate"];
  $reclevel = $_POST["reclevel"];
  $recyear = $_POST["recyear"];
  $recprog1 = $_POST["recprog1"];
  $recprog2 = $_POST["recprog2"];
  $recprog3 = $_POST["recprog3"];
  $recgpa = $_POST["recgpa"];
  $recremote = $_POST["recremote"];
  if ($recremote == 0) {
    $recaddress = $_POST["recaddress"];
    $reccity = $_POST["reccity"];
    $recregion = $_POST["recregion"];
    $reccountry = $_POST["reccountry"];
  }

  $host = "localhost";
  $dbUsername = "root";
  $dbPassword = "";
  $dbName = "website";
  $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

  if ($conn->query("UPDATE position SET Position = '$recposition', Type = '$rectype', Skill1 = '$recskill1', Skill2 = '$recskill2', Skill3 = '$recskill3', Skill4 = '$recskill4', Skill5 = '$recskill5', Start = '$recdate' WHERE Email = '$email'")) {
    //echo "Works";
    if ($conn->query("UPDATE position SET Level = '$reclevel', Year = '$recyear', Program1 = '$recprog1', Program2  = '$recprog2', Program3 = '$recprog3', GPA = '$recgpa', Remote = '$recremote' WHERE Email = '$email'")) {
      if ($conn->query("UPDATE position SET Address = '$recaddress', City = '$reccity', Region = '$recregion', Country = '$reccountry' WHERE Email = '$email'")) {
        $updated = True;
      }

    }
  }
  //Calculating match score
  $result = $conn-> query("SELECT * FROM user_login");
  $score = 0;
  while($row = $result -> fetch_array(MYSQLI_ASSOC)) {
    $recposition = strtolower(preg_replace('/\s+/', '', $recposition));
    if (strcmp($recposition, strtolower(preg_replace('/\s+/', '', $row['Position1']))) == 0 or strcmp($recposition, strtolower(preg_replace('/\s+/', '', $row['Position2'])))==0 or strcmp($recposition, strtolower(preg_replace('/\s+/', '', $row['Position3'])))==0) {
      $score += 15;
    }
    if (strcmp($rectype, $row['Type1'])==0 or strcmp($rectype, $row['Type2'])==0 or strcmp($rectype, $row['Type3'])==0) {
      $score += 5;
    }
    else {
      $score += 3;
    }
    $recskill1 = strtolower(preg_replace('/\s+/', '', $recskill1));
    $recskill2 = strtolower(preg_replace('/\s+/', '', $recskill2));
    $recskill3 = strtolower(preg_replace('/\s+/', '', $recskill3));
    $recskill4 = strtolower(preg_replace('/\s+/', '', $recskill4));
    $recskill5 = strtolower(preg_replace('/\s+/', '', $recskill5));

    if (strcmp($recskill1, strtolower(preg_replace('/\s+/', '', $row['Skill1']))) == 0 or strcmp($recskill1, strtolower(preg_replace('/\s+/', '', $row['Skill2'])))==0 or strcmp($recskill1, strtolower(preg_replace('/\s+/', '', $row['Skill3'])))==0 or strcmp($recskill1, strtolower(preg_replace('/\s+/', '', $row['Skill4'])))==0 or strcmp($recskill1, strtolower(preg_replace('/\s+/', '', $row['Skill5'])))==0) {
      $score += 5;
    }
    if (strcmp($recskill2, strtolower(preg_replace('/\s+/', '', $row['Skill1']))) == 0 or strcmp($recskill2, strtolower(preg_replace('/\s+/', '', $row['Skill2'])))==0 or strcmp($recskill2, strtolower(preg_replace('/\s+/', '', $row['Skill3'])))==0 or strcmp($recskill2, strtolower(preg_replace('/\s+/', '', $row['Skill4'])))==0 or strcmp($recskill2, strtolower(preg_replace('/\s+/', '', $row['Skill5'])))==0) {
      $score += 5;
    }
    if (strcmp($recskill3, strtolower(preg_replace('/\s+/', '', $row['Skill1']))) == 0 or strcmp($recskill3, strtolower(preg_replace('/\s+/', '', $row['Skill2'])))==0 or strcmp($recskill3, strtolower(preg_replace('/\s+/', '', $row['Skill3'])))==0 or strcmp($recskill3, strtolower(preg_replace('/\s+/', '', $row['Skill4'])))==0 or strcmp($recskill3, strtolower(preg_replace('/\s+/', '', $row['Skill5'])))==0) {
      $score += 5;
    }
    if (strcmp($recskill4, strtolower(preg_replace('/\s+/', '', $row['Skill1']))) == 0 or strcmp($recskill4, strtolower(preg_replace('/\s+/', '', $row['Skill2'])))==0 or strcmp($recskill4, strtolower(preg_replace('/\s+/', '', $row['Skill3'])))==0 or strcmp($recskill4, strtolower(preg_replace('/\s+/', '', $row['Skill4'])))==0 or strcmp($recskill4, strtolower(preg_replace('/\s+/', '', $row['Skill5'])))==0) {
      $score += 5;
    }
    if (strcmp($recskill5, strtolower(preg_replace('/\s+/', '', $row['Skill1']))) == 0 or strcmp($recskill5, strtolower(preg_replace('/\s+/', '', $row['Skill2'])))==0 or strcmp($recskill5, strtolower(preg_replace('/\s+/', '', $row['Skill3'])))==0 or strcmp($recskill5, strtolower(preg_replace('/\s+/', '', $row['Skill4'])))==0 or strcmp($recskill5, strtolower(preg_replace('/\s+/', '', $row['Skill5'])))==0) {
      $score += 5;
    }
    if ($row['Level'] >= $reclevel) {
      $score += 15;
    }
    if ($row['Year'] >= $recyear) {
      $score += 10;
    }
    if ($conn->query("UPDATE user_login SET Score = '$score' WHERE Email = '$row['Email']'")) {
      $updated = True;
    }
    else {
      $updated = False;
    }
  }
}
if ($updated) {
  header("Location: messages.html");
}
else {
  echo "Submit button is not set";
}

$conn->close();
?>
