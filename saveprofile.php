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

    for ($i = 0; $i < 7; $i++) {
      if (isset($_POST['Skill'][$i])) {
        array_push($skills, $_POST['Skill'][$i]);
      }
      else {
        array_push($skills, NULL);
      }
    }

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
    //echo $filename;
	// Select file type
  	$imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

  	// valid file extensions
  	$extensions_arr = array("jpg","jpeg","png","gif");

  	// Check extension
  	if(in_array($imageFileType, $extensions_arr)) {

  	// Upload files and store in database
      if(!empty($filename)) {
      	if(move_uploaded_file($_FILES["image"]["tmp_name"],'img/'.$filename)){
      		// Image db insert sql

  // Use unlink() function to delete a file
          if (!unlink($image)) {
              echo ("$image cannot be deleted due to an error");
          }
          else {
              echo ("$image has been deleted");
          }
      		$insert = "UPDATE user_login SET Image = '$filename' WHERE Email = '$email'";
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
    $bio = $conn->real_escape_string($_POST['Bio']);

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
        $experience[$i]->end = $_POST['EndDate'][$i];
        $expobject[$i] = serialize($experience[$i]);

        continue;
      }
    }

    //if (isset($image)) {
      //$conn->query("UPDATE user_login SET Image = '$image' WHERE Email = '$email'");
    //}
    if ($conn->query("UPDATE user_login SET Experience1 = '$expobject[0]', Experience2 = '$expobject[1]', Experience3 = '$expobject[2]', Experience4 = '$expobject[3]', Experience5 = '$expobject[4]', GPA = '$gpa', Bio = '$bio' WHERE Email = '$email' ")) {

      if ($conn->query("UPDATE user_login SET FirstName = '$firstname', LastName = '$lastname', Phone = '$phone', Country = '$country', Email2 = '$email2', Region = '$region', City = '$city', Level = '$level', School = '$school', Program = '$program', Year = '$year' WHERE Email = '$email' ")) {
        echo "Works";
        if ($conn->query("UPDATE user_login SET Skill1 = '$skills[0]', Skill2 = '$skills[1]', Skill3 = '$skills[2]', Skill4 = '$skills[3]', Skill5 = '$skills[4]', Skill6 = '$skills[5]', Skill7 = '$skills[6]' WHERE Email = '$email' ")) {
          echo "Works";
          if ($conn->query("UPDATE user_login SET Award1 = '$awards[0]', Award2 = '$awards[1]', Award3 = '$awards[2]', Award4 = '$awards[3]', Award5 = '$awards[4]', Award6 = '$awards[5]', Award7 = '$awards[6]' WHERE Email = '$email' ")) {
            echo "Works";
            if ($conn->query("UPDATE user_login SET Position1 = '$positions[0]', Position2 = '$positions[1]', Position3 = '$positions[2]', Type1 = '$types[0]', Type2 = '$types[1]', Type3 = '$types[2]' WHERE Email = '$email' ")) {
              header("Location: search.php");
            }
            else {
              echo "Failed";
            }
          }
        }
      }
    }
  }
  else {
    echo "All field are required.";
    die();
  }
}
else {
  echo "Submit button is not set";
}
$exp->free_result();
$conn->close();
?>
