<html>
  <?php
  session_start();
  if(isset($_SESSION["Email"]) && $_SESSION['loggedin'] == true) {

    $username = $_SESSION['Email'];

    $conn =  new mysqli("localhost","root", "", "website");
    // Check connection
    if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $result = $conn-> query("SELECT * FROM user_login");


    while($row = $result -> fetch_array(MYSQLI_ASSOC)) {
      if($row["Email"] == $username) {
        $firstname = $row['FirstName'];
        $bio = $row['Bio'];
        $lastname = $row['LastName'];
        $email = $row['Email2'];
        $phone = $row['Phone'];
        $country = $row['Country'];
        $region = $row['Region'];
        $city = $row['City'];
        $image = $row['Image'];
        if(!empty($image)) {
          $profilepic = "img/".$image;
        }
        else {
          $profilepic = "img/defaultprofile.PNG";
        }
      }
    }

  }
  ?>
  <head>
    <meta charset = "UTF-8">
    <meta name = "description" content = "This is my first experimental website">

    <title>First Website</title>
    <link rel = "stylesheet" href = "styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

  </head>
  <body>
    <header>

      <a href="home.html"><button class = "homebutton">Home</button></a>
      <nav>
        <ul class = "topnavlinks">
          <li><a href="search.php">Search</a></li>
          <li><a href="messages.php">Messages</a></li>
          <li><a class="active" href="editprofile.php">Profile</a></li>
        </ul>
      </nav>
      <a href="logout.php"><button class = "logoutbutton">Logout</button></a>

    </header>
    <div class = "bottomnav">
      <div class = "bottomnavcontents">
        <a class = "active" href="editprofile.php"><div id = "editprofile">Edit Profile</div></a>
        <a href="viewprofile.php"><div id = "viewprofile" class = "active">View Profile</div></a>
      </div class = "bottomnavcontents">
    </div>
    <div class = "viewprofilecontainer">
      <div class = "viewprofilecontents">
        <div class = "viewbasicinfo">
          <div class = "viewimage">
            <img src="<?php echo $profilepic; ?>" height="200" width="200" border-radius="50%" background-color="white" class="imgthumbnail" />
          </div>
          <div class = "viewbasicinfocontents">
            <div class = "viewname">
              <?php echo $firstname." ".$lastname; ?>
            </div>
            <hr style = "margin-left: 10px; width: 100%; margin-bottom: 10px;" color = "darkblue" size = "3">
            <div class = "viewcontact">
              <?php if (!is_null($email)) { echo "Email: ".$email; }
                    if (!is_null($phone) and !empty($phone)) { echo " | Phone: ".$phone; }?>
            </div>
            <div class = "viewlocation">
              <?php if (!is_null($city) and !empty($city)) { echo $city." "; } ?>
              <?php if (!is_null($region) and !empty($region)) { echo $region." "; } ?>
              <?php if (!is_null($country) and !empty($country)) { echo $country; } ?>
            </div>
            <div class = "viewbio"><p style = "padding: 5px;">
              <?php if (!is_null($bio) and !empty($bio)) { echo $bio; } ?>
            </p></div>
          </div>
        </div>

        <div class = "viewskills">
          <?php
          $skll = $conn->query("SELECT Email, Skill1, Skill2, Skill3, Skill4, Skill5, Skill6, Skill7 FROM user_login");
          while($row = $skll -> fetch_array(MYSQLI_NUM)) {
            if($row[0] == $email) {
              for ($i = 1; $i < 7; $i++) {
                if (is_null($row[$i]) or empty($row[$i]) or $row[$i] == "NULL") {
                  continue;
                }
                else {
                  echo "<div class = skilltag><div class = skillname>".unserialize($row[$i])->skill."</div><div class = skillyear>".unserialize($row[$i])->year."</div></div>";
                }
              }
            }
          }
          ?>
        </div>
        <div class = "viewexperience">
          <?php
          $exp = $conn->query("SELECT Email, Experience1, Experience2, Experience3, Experience4, Experience5 FROM user_login");
          while($row = $exp -> fetch_array(MYSQLI_NUM)) {
            if($row[0] == $email) {
              for ($i = 1; $i < 5; $i++) {
                if (is_null($row[$i]) or empty($row[$i]) or $row[$i] == "NULL") {

                  continue;
                }
                else {
                  echo "<div class = experiencetag><div class = experiencerole>".unserialize($row[$i])->role."</div><div class = experiencecompany>".unserialize($row[$i])->company."</div><div class = experiencestartend>".unserialize($row[$i])->start." - ".unserialize($row[$i])->end."</div></div>";
                }
              }
            }
          }
          ?>
        </div>
        <div class = "vieweducation">
        </div>
      </div>
    </div>
  </body>

</html>
