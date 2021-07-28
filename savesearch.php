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
  //echo "hi";
  //Calculating match score
  $select = "SELECT * FROM user_login";
  $result = $conn-> query($select);


  function strictlyEqualAndNotNull($var1, $var2) {
    return (!empty($var1) && !is_null($var2) && $var1 === $var2);
  }

  function formatString($var) {
    return (strtolower(preg_replace('/\s+/', '', $var)));
  }

  $recposition = formatString($recposition);
  $recskill1 = formatString($recskill1);
  $recskill2 = formatString($recskill2);
  $recskill3 = formatString($recskill3);
  $recskill4 = formatString($recskill4);
  $recskill5 = formatString($recskill5);
  $recprog1 = formatString($recprog1);
  $recprog2 = formatString($recprog1);
  $recprog3 = formatString($recprog1);


  while($row = $result -> fetch_array(MYSQLI_ASSOC)) {
    $score = 0;
    //echo $row['Email'];
    if (strictlyEqualAndNotNull($recposition, formatString($row['Position1'])) or strictlyEqualAndNotNull($recposition, formatString($row['Position2'])) or strictlyEqualAndNotNull($recposition, formatString($row['Position3']))) {
      $score += 15;
    }
    if (strictlyEqualAndNotNull($rectype, $row['Type1']) or strictlyEqualAndNotNull($rectype, $row['Type2']) or strictlyEqualAndNotNull($rectype, $row['Type3'])) {
      $score += 5;
      //echo $row['Email'];
    }
    else {
      $score += 3;
    }
    //echo $score;

    if (strictlyEqualAndNotNull($recskill1, formatString($row['Skill1'])) or strictlyEqualAndNotNull($recskill1, formatString($row['Skill2'])) or strictlyEqualAndNotNull($recskill1, formatString($row['Skill3'])) or strictlyEqualAndNotNull($recskill1, formatString($row['Skill4'])) or strictlyEqualAndNotNull($recskill1, formatString($row['Skill5']))) {
      $score += 5;

    }
    if (strictlyEqualAndNotNull($recskill2, formatString($row['Skill1'])) or strictlyEqualAndNotNull($recskill2, formatString($row['Skill2'])) or strictlyEqualAndNotNull($recskill2, formatString($row['Skill3'])) or strictlyEqualAndNotNull($recskill2, formatString($row['Skill4'])) or strictlyEqualAndNotNull($recskill2, formatString($row['Skill5']))) {
      $score += 5;
    }
    if (strictlyEqualAndNotNull($recskill3, formatString($row['Skill1'])) or strictlyEqualAndNotNull($recskill3, formatString($row['Skill2'])) or strictlyEqualAndNotNull($recskill3, formatString($row['Skill3'])) or strictlyEqualAndNotNull($recskill3, formatString($row['Skill4'])) or strictlyEqualAndNotNull($recskill3, formatString($row['Skill5']))) {
      $score += 5;
    }
    if (strictlyEqualAndNotNull($recskill4, formatString($row['Skill1'])) or strictlyEqualAndNotNull($recskill4, formatString($row['Skill2'])) or strictlyEqualAndNotNull($recskill4, formatString($row['Skill3'])) or strictlyEqualAndNotNull($recskill4, formatString($row['Skill4'])) or strictlyEqualAndNotNull($recskill4, formatString($row['Skill5']))) {
      $score += 5;
    }
    if (strictlyEqualAndNotNull($recskill5, formatString($row['Skill1'])) or strictlyEqualAndNotNull($recskill5, formatString($row['Skill2'])) or strictlyEqualAndNotNull($recskill5, formatString($row['Skill3'])) or strictlyEqualAndNotNull($recskill5, formatString($row['Skill4'])) or strictlyEqualAndNotNull($recskill5, formatString($row['Skill5']))) {
      $score += 5;
    }
    if ($row['Level'] >= $reclevel) {
      $score += 15;
    }
    if ($row['Year'] >= $recyear) {
      $score += 10;
    }


    if (strictlyEqualAndNotNull($recprog1, formatString($row['Program'])) or strictlyEqualAndNotNull($recprog2, formatString($row['Program'])) or strictlyEqualAndNotNull($recprog3, formatString($row['Program']))) {
      $score += 10;

    }
    $update = "UPDATE user_login SET Score = '$score' WHERE Email='".$row['Email']."' ";
    if ($conn->query($update)) {
      $updated = True;
    }
    else {
      $updated = False;
    }
  }
}
if ($updated) {
  echo "Success";
  header("Location: messages.html");
}
else {
  echo "Submit button is not set";
}

$conn->close();
?>
