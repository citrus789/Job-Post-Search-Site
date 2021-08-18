<?php
//error_reporting(0);
session_start();
if(isset($_SESSION["Email"]) || $_SESSION['loggedin'] == true) {
  $username = $_SESSION['Email'];

  $conn = new mysqli("localhost","root", "", "website");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $result = $conn-> query("SELECT * FROM user_login");


  while($row = $result -> fetch_array(MYSQLI_ASSOC)) {
    if($row["Email"] == $username) {
      if (!empty($row['Image'])) {
        $image = "img/".$row['Image'];
      }
    }
  }
}

if (isset($_POST['saveprofile'])) {
  if (isset($_POST['Email']) && isset($_POST['FirstName']) && isset($_POST['LastName'])) {
    $email = $_SESSION['Email'];

    $email2 = $_POST['Email'];
    $firstname = $_POST['FirstName'];
    $lastname = $_POST['LastName'];
    $phone = $_POST['Phone'];
    $country = $_POST['Country'];
    $region = $_POST['Region'];
    $city = $_POST['City'];
    $level = $_POST['Level'];
    $school = $_POST['School'];
    $program = $_POST['Program'];
    $year = $_POST['Year'];
    $gpa = $_POST['GPA'];
    $skills = array();
    $type = $_POST['Type'];
    $awards = array();
    $positions = array();
    $types = array();
    $current = array();


    for ($i = 0; $i < 7; $i++) {
      if (isset($_POST['Award'][$i])) {
        array_push($awards, $_POST['Award'][$i]);
      }
      else {
        array_push($awards, NULL);
      }
    }

    for ($i = 0; $i < 3; $i++) {
      if (isset($_POST['Position'][$i])) {
        array_push($positions, $_POST['Position'][$i]);
      }
      else {
        array_push($positions, NULL);
      }
    }

    for ($i = 0; $i < 3; $i++) {
      if (isset($_POST['Type'][$i])) {
        array_push($types, $_POST['Type'][$i]);
      }
      else {
        array_push($types, NULL);
      }
    }

    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "website";
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    $filename = $_FILES['image']['name'];
    //$image = "img/".$_POST['image'];
    echo $filename;
	// Select file type
  	$imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

  	// valid file extensions
  	$extensions_arr = array("jpg","jpeg","png","gif", "jfif");
    function clean($string) {
      $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

      return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
  	// Check extension
  	if(in_array($imageFileType, $extensions_arr)) {
      // echo "good file";
  	  // Upload files and store in database
      if(!empty($filename)) {
        $temp = explode(".", $_FILES["image"]["name"]);
        $newfilename = clean($email) . '.' . end($temp);
        echo $newfilename;
      	if(move_uploaded_file($_FILES["image"]["tmp_name"],'img/'.$newfilename)){
      		// Image db insert sql
          echo "moved";
          // Use unlink() function to delete a file
          if (!unlink($image)) {
            echo ("$image cannot be deleted due to an error");
          }
          else {
            echo ("$image has been deleted");
          }
      		$insert = "UPDATE user_login SET Image = '$newfilename' WHERE Email = '$email'";
      		if($conn->query($insert)) {
      		  echo 'Data inserted successfully';
      		}
      		else {
      		  echo 'Error: '.mysqli_error($conn);
      		}
        }

        else {
      		echo 'Error in uploading file - '.$_FILES['image']['name'].'';
      	}
      }
  	}
    //echo "Current = ".$_POST['Current'][1];
    $bio = $_POST['Bio'];

    $exp = $conn->query("SELECT Experience1, Experience2, Experience3, Experience4, Experience5 FROM user_login");
    $row = $exp -> fetch_array(MYSQLI_NUM);
    $experience = array();
    $expobject = array();
    $count = 0;
    for ($i = 0; $i < 5; $i++) {
      if (empty($_POST['Role'][$i]) && empty($_POST['Company'][$i])) {
        $expobject[$i] = "NULL";
        continue;
      }
      else {
        $experience[$i] = new stdClass();
        $experience[$i]->role = $_POST['Role'][$i];
        $experience[$i]->company = $_POST['Company'][$i];
        $experience[$i]->start = $_POST['StartDate'][$i];
        $experience[$i]->description = nl2br($_POST['ExperienceDescription'][$i]);
        if ($_POST['Current'][$i] != "Present") {
          $experience[$i]->end = $_POST['EndDate'][$i];
        }
        else {
          $experience[$i]->end = "Present";
        }
        // echo $experience[$i]->end;
        $expobject[$i] = serialize($experience[$i]);
        continue;
      }
    }

    $edu = $conn->query("SELECT Education1, Education2, Education3 FROM user_login");
    $row = $edu -> fetch_array(MYSQLI_NUM);
    $education = array();
    $eduobject = array();
    $count = 0;
    for ($i = 0; $i < 3; $i++) {
      if (empty($_POST['Level'][$i]) && empty($_POST['School'][$i]) && empty($_POST['Program'][$i])) {
        $eduobject[$i] = "NULL";
        continue;
      }
      else {
        $education[$i] = new stdClass();
        $education[$i]->level = $_POST['Level'][$i];
        $education[$i]->school = $_POST['School'][$i];
        $education[$i]->program = $_POST['Program'][$i];
        $education[$i]->year = $_POST['Year'][$i];
        $education[$i]->gpa = $_POST['GPA'][$i];
        $education[$i]->description = nl2br($_POST['EducationDescription'][$i]);


        $eduobject[$i] = serialize($education[$i]);
        continue;
      }
    }

    $skll = $conn->query("SELECT Skill1, Skill2, Skill3, Skill4, Skill5, Skill6, Skill7 FROM user_login");
    $row = $skll -> fetch_array(MYSQLI_NUM);
    $skill = array();
    $skillobject = array();
    $count = 0;
    for ($i = 0; $i < 7; $i++) {
      if (empty($_POST['Skill'][$i])) {
        $skillobject[$i] = "NULL";
        continue;
      }
      else {
        $skill[$i] = new stdClass();
        $skill[$i]->skill = $_POST['Skill'][$i];
        $skill[$i]->year = $_POST['YearsExp'][$i];
        $skillobject[$i] = serialize($skill[$i]);

        continue;
      }
    }

    //if (isset($image)) {
      //$conn->query("UPDATE user_login SET Image = '$image' WHERE Email = '$email'");
    //}
    $stmt1 = $conn->prepare("UPDATE user_login SET FirstName = ?, LastName = ?,  Bio = ?, Phone = ?, Country = ?, Email2 = ?, Region = ?, City = ?, Education1=?, Education2=?, Education3=?, Skill1 = ?, Skill2 = ?, Skill3 = ?, Skill4 = ?, Skill5 = ?, Skill6 = ?, Skill7 = ? WHERE Email = ?");

    $stmt1->bind_param("sssssssssssssssssss", $firstname, $lastname, $bio, $phone, $country, $email2, $region, $city, $eduobject[0], $eduobject[1], $eduobject[2], $skillobject[0], $skillobject[1], $skillobject[2], $skillobject[3], $skillobject[4], $skillobject[5], $skillobject[6], $email);


    if ($stmt1->execute()) {
      $stmt1->close();
      $stmt2 = $conn->prepare("UPDATE user_login SET Experience1=?, Experience2=?, Experience3=?, Experience4=?, Experience5=?, Award1 = ?, Award2 = ?, Award3 = ?, Award4 = ?, Award5 = ?, Award6 = ?, Award7 = ?, Position1 = ?, Position2 = ?, Position3 = ?, Type1 = ?, Type2 = ?, Type3 = ? WHERE Email = ?");
      $stmt2->bind_param("sssssssssssssssssss", $expobject[0], $expobject[1], $expobject[2], $expobject[3], $expobject[4], $awards[0], $awards[1], $awards[2], $awards[3], $awards[4], $awards[5], $awards[6], $positions[0], $positions[1], $positions[2], $types[0], $types[1], $types[2], $email);
      if ($stmt2->execute()) {
        $stmt2->close();
        header("Location: viewprofile.php");
      }
    }
    else {
      echo "Failed";

    }

    // if ($conn->query("UPDATE user_login SET Experience1 = '$expobject[0]', Experience2 = '$expobject[1]', Experience3 = '$expobject[2]', Experience4 = '$expobject[3]', Experience5 = '$expobject[4]', Bio = '$bio' WHERE Email = '$email' ")) {
    //
    //   if ($conn->query("UPDATE user_login SET FirstName = '$firstname', LastName = '$lastname', Phone = '$phone', Country = '$country', Email2 = '$email2', Region = '$region', City = '$city', Education1 = '$eduobject[0]', Education2 = '$eduobject[1]', Education3 = '$eduobject[2]' WHERE Email = '$email' ")) {
    //     //echo "Works";
    //     if ($conn->query("UPDATE user_login SET Skill1 = '$skillobject[0]', Skill2 = '$skillobject[1]', Skill3 = '$skillobject[2]', Skill4 = '$skillobject[3]', Skill5 = '$skillobject[4]', Skill6 = '$skillobject[5]', Skill7 = '$skillobject[6]' WHERE Email = '$email' ")) {
    //       //echo "Works";
    //       if ($conn->query("UPDATE user_login SET Award1 = '$awards[0]', Award2 = '$awards[1]', Award3 = '$awards[2]', Award4 = '$awards[3]', Award5 = '$awards[4]', Award6 = '$awards[5]', Award7 = '$awards[6]' WHERE Email = '$email' ")) {
    //         //echo "Works";
    //         if ($conn->query("UPDATE user_login SET Position1 = '$positions[0]', Position2 = '$positions[1]', Position3 = '$positions[2]', Type1 = '$types[0]', Type2 = '$types[1]', Type3 = '$types[2]' WHERE Email = '$email' ")) {

    //       }
    //     }
    //   }
    // }
  }
  else {
    echo "All field are required.";
    die();
  }
}
else {
  echo "Submit button is not set";
}
$skll->free_result();
$exp->free_result();
$conn->close();
?>
