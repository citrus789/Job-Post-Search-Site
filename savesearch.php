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
  $skillyear1 = $_POST["skillyear1"];
  $skillyear2 = $_POST["skillyear2"];
  $skillyear3 = $_POST["skillyear3"];
  $skillyear4 = $_POST["skillyear4"];
  $skillyear5 = $_POST["skillyear5"];
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
  if ($conn->connect_error) {
      die('Could not connect to database.');
  }
  if ($conn->query("UPDATE position SET Position = '$recposition', Type = '$rectype', Skill1 = '$recskill1', Skill2 = '$recskill2', Skill3 = '$recskill3', Skill4 = '$recskill4', Skill5 = '$recskill5', Start = '$recdate' WHERE Email = '$email'")) {
    if ($conn->query("UPDATE position SET Years1 = '$skillyear1', Years2 = '$skillyear2', Years3 = '$skillyear3', Years4 = '$skillyear4', Years5 = '$skillyear5' WHERE Email = '$email'")) {

    //echo "Works";
      if ($conn->query("UPDATE position SET Level = '$reclevel', Year = '$recyear', Program1 = '$recprog1', Program2  = '$recprog2', Program3 = '$recprog3', GPA = '$recgpa', Remote = '$recremote' WHERE Email = '$email'")) {
        if ($conn->query("UPDATE position SET Address = '$recaddress', City = '$reccity', Region = '$recregion', Country = '$reccountry' WHERE Email = '$email'")) {
          $updated = True;
        }
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

  function isMatch($name, $time, $skill1, $skill2, $skill3, $skill4, $skill5, $skill6, $skill7) {
    $num = 0;

    if (strictlyEqualAndNotNull($name, formatString($skill1->skill)) or strictlyEqualAndNotNull($name, formatString($skill2->skill)) or strictlyEqualAndNotNull($name, formatString($skill3->skill)) or strictlyEqualAndNotNull($name, formatString($skill4->skill)) or strictlyEqualAndNotNull($name, formatString($skill5->skill)) or strictlyEqualAndNotNull($name, formatString($skill6->skill)) or strictlyEqualAndNotNull($name, formatString($skill7->skill))) {
      $num += 5;
    }
    if ((strictlyEqualAndNotNull($name, formatString($skill1->skill)) and $skill1->year >= $time) or (strictlyEqualAndNotNull($name, formatString($skill2->skill)) and $skill2->year >= $time) or (strictlyEqualAndNotNull($name, formatString($skill3->skill)) and $skill3->year >= $time) or (strictlyEqualAndNotNull($name, formatString($skill4->skill)) and $skill4->year >= $time) or (strictlyEqualAndNotNull($name, formatString($skill5->skill)) and $skill5->year >= $time) or (strictlyEqualAndNotNull($name, formatString($skill6->skill)) and $skill6->year >= $time) or (strictlyEqualAndNotNull($name, formatString($skill7->skill)) and $skill7->year >= $time)) {
      $num += 2;
    }
    return ($num);
  }

  function unpackObject($object) {
    if ($object === "NULL" or empty($object) or is_null($object)) {
      $obj = new stdClass();
      $obj->skill = "Hello World";
      $obj->year = "0";
      //echo $obj->skill;
      $object = serialize($obj);
      //return ($obj);
    }
    return unserialize($object);
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


  $column = mysqli_query($conn, "SHOW COLUMNS FROM `score` LIKE '$email'");
  $exists = (mysqli_num_rows($column))?TRUE:FALSE;

  if (!$exists) {
    $stmt = $conn->prepare("ALTER TABLE score ADD `$email` VARCHAR(100)");
  }
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
    if ($row['Level'] >= $reclevel) {
      $score += 15;
    }
    if ($row['Year'] >= $recyear) {
      $score += 10;
    }
    if (strictlyEqualAndNotNull($recprog1, formatString($row['Program'])) or strictlyEqualAndNotNull($recprog2, formatString($row['Program'])) or strictlyEqualAndNotNull($recprog3, formatString($row['Program']))) {
      $score += 10;
    }
    if ($row['GPA'] >= $recgpa) {
      $score += 10;
    }
    $skill1 = unpackObject($row['Skill1']);
    $skill2 = unpackObject($row['Skill2']);
    $skill3 = unpackObject($row['Skill3']);
    $skill4 = unpackObject($row['Skill4']);
    $skill5 = unpackObject($row['Skill5']);
    $skill6 = unpackObject($row['Skill6']);
    $skill7 = unpackObject($row['Skill7']);
    //echo $skill1;

    $score += isMatch($recskill1, $skillyear1, $skill1, $skill2, $skill3, $skill4, $skill5, $skill6, $skill7);
    $score += isMatch($recskill2, $skillyear2, $skill1, $skill2, $skill3, $skill4, $skill5, $skill6, $skill7);
    $score += isMatch($recskill3, $skillyear3, $skill1, $skill2, $skill3, $skill4, $skill5, $skill6, $skill7);
    $score += isMatch($recskill4, $skillyear4, $skill1, $skill2, $skill3, $skill4, $skill5, $skill6, $skill7);
    $score += isMatch($recskill5, $skillyear5, $skill1, $skill2, $skill3, $skill4, $skill5, $skill6, $skill7);

    if ($recremote == 1) {
      $score += 10;
    }
    else {
      if (strictlyEqualAndNotNull(formatString($reccountry), formatString($row['Country']))) {
        $score += 5;
        if (strictlyEqualAndNotNull(formatString($reccity), formatString($row['City']))) {
          $score += 5;
        }
      }
    }
    echo $row['Email'].$score;
    if ($exists or $stmt->execute()) {
      $update = sprintf("UPDATE score SET `%s` = '$score' WHERE Email = '".$row['Email']."'", $conn->real_escape_string($email));
    }
    if ($conn->query($update)) {
      $updated = True;
    }
    else {
      $updated = False;
      echo "Could not update";
    }
  }
}
if ($updated) {
  echo "Success";
  header("Location: searchresults.php");
}
else {
  echo "Submit button is not set";
}

$conn->close();
?>
