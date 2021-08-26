<?php
session_start();
if(isset($_SESSION["Email"]) || $_SESSION['loggedin'] == true) {
  $username = $_SESSION['Email'];

  $conn = new mysqli("localhost","root", "", "website");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
}
?>
<html>
  <head>
    <meta charset = "UTF-8">
    <meta name = "description" content = "This is my first experimental website">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
          <li><a class="active" href="messages.php">Postings</a></li>
          <li><a href="editprofile.php">Profile</a></li>
        </ul>
      </nav>
      <a href="logout.php"><button class = "logoutbutton">Logout</button></a>

    </header>
    <div class = "bottomnav">
      <div class = "bottomnavcontentspostings">
        <a href="messages.php"><div id = "editprofile">Postings</div></a>
        <a href="sentpostings.php"><div id = "viewprofile" class = "active" >Postings Sent</div></a>
        <a  href="sentapplications.php"><div id = "viewprofile">Applications Sent</div></a>
      </div>
    </div>
    <div class = "sentpostingscontainer">
      <div class = "sentpostingscontents">

      <?php
      $select = "SELECT * FROM sent_postings";
      $result = $conn->query($select);
      while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $numcolumns = $result->field_count;

        if ($row[0] == $username) {
          for ($i = 1; $i < $numcolumns; $i++) {
            if (!empty($row[$i])) { echo $i; ?>

              <div class = "eachsentposting" id = "sentposting<?php echo $i;?>">
                <div class = "eachsentpostingheader">
                  <div class = "sentpostingtitle">
                    <?php echo unserialize($row[$i])->position." at ".unserialize($row[$i])->company; ?>
                  </div>
                  <div class = eachsentpostingdetails>
                    <?php
                    if (isset(unserialize($row[$i])->remote)) {
                      echo "<div class = ispostingremote><div class = postingremote>".unserialize($row[$i])->remote."</div></div>";
                    }

                    if (isset(unserialize($row[$i])->type)) {
                      echo "<div class = 'ispostingremote'><div class = 'postingremote'>".unserialize($row[$i])->type."</div></div>";
                    }
                    ?>
                    <div class = "ispostingremote">
                      <div class = "postingremote"><?php if (unserialize($row[$i])->remote == 'In Person') {
                        echo "".unserialize($row[$i])->city;
                        if (!is_null(unserialize($row[$i])->region)) {
                          echo ", ".unserialize($row[$i])->region;
                        }
                        if (!is_null(unserialize($row[$i])->country)) {
                          echo ", ".unserialize($row[$i])->country;
                        }
                      }?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class = "applicantslist">
               <?php
               $postingname = unserialize($row[$i])->position.unserialize($row[$i])->company;
               $select = "SELECT Email, `$postingname` FROM sent_application";
               $result = $conn->query($select);
               if ($result) {
                 while ($applicants = $result->fetch_array(MYSQLI_ASSOC)) {
                   if ($applicants[$postingname] != NULL and !empty($applicants[$postingname])) {
                     $applicantemail = $applicants['Email'];
                     // echo $applicantemail;
                     $selectuser = "SELECT * FROM user_login";

                     $resultuser = $conn->query($selectuser);
                     while ($userinfo = $resultuser->fetch_array(MYSQLI_ASSOC)) {
                       if ($userinfo['Email'] == $applicantemail) {
                         echo "<div class = 'singleapplicant'>";
                         echo "<div class = 'singleapplicantname'>".$userinfo['FirstName']." ".$userinfo['LastName']."</div>";
                         $profilepic = "img/".$userinfo["Image"];
                         $firstname = $userinfo["FirstName"];
                         $lastname = $userinfo["LastName"];
                         $email = $applicantemail;
                         $phone = $userinfo["Phone"];
                         $city = $userinfo["City"];
                         $country = $userinfo["Country"];
                         $region = $userinfo["Region"];
                         $bio = $userinfo["Bio"];
                         echo "<div class = singleapplicantdocscontainer><div class = singleapplicantdocs>";
                         if (unserialize($applicants[$postingname])->resume == $applicantemail) {
                           echo "<button class = applicantresume id = '".$applicantemail."profile' onclick = showprofile('".$applicantemail."')>View Resume</button>";
                         }
                         else {
                           echo "<a href = ".unserialize($applicants[$postingname])->resume."><button class = applicantresume>Download Resume</button></a>";
                         }
                         if (strpos(unserialize($applicants[$postingname])->cv, "coverletter/".$applicantemail."/".$postingname) !== false) {
                           echo "<a href = ".unserialize($applicants[$postingname])->cv."><button class = applicantresume>Download Cover Letter</button></a>";
                         }
                         else {
                           echo "<button class = applicantresume id = '".$applicantemail."cv' onclick = showcv('".$applicantemail."')>View Cover Letter</button>";
                         }
                         echo "</div></div>";
                         ?>
                         <div class = "viewresumecontents" id = viewcvcontents<?php echo $email;?> style = "display: none">
                           <?php echo unserialize($applicants[$postingname])->cv;?>
                         </div>
                         <div class = "viewresumecontents" id = viewresumecontents<?php echo $email;?> style = "display: none;">
                           <div class = "viewbasicresume">
                             <div class = "viewimage">
                               <img src="<?php echo $profilepic; ?>" height="200" width="200" border-radius="50%" background-color="white" class="imgthumbnail" />
                             </div>
                             <div class = "viewbasicresumecontents">
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
                               <div class = "viewbio"><p style = "padding: 5px; overflow: visible">
                                 <?php if (!is_null($bio) and !empty($bio)) { echo $bio; } ?>
                               </p></div>
                             </div>
                           </div>
                           <div class = "viewskillstitle" >
                             <div style = "font-size: 35px; padding: 0% 3%">Skills</div>
                             <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
                           </div>
                           <div class = "viewskills">
                             <?php
                             $skll = $conn->query("SELECT Email, Skill1, Skill2, Skill3, Skill4, Skill5, Skill6, Skill7 FROM user_login");
                             while($row = $skll -> fetch_array(MYSQLI_NUM)) {
                               if($row[0] == $email) {
                                 for ($j = 1; $j < 8; $j++) {
                                   if (is_null($row[$j]) or empty($row[$j]) or $row[$j] == "NULL") {
                                     continue;
                                   }
                                   else {
                                     echo "<div class = skilltag><div class = eachskill>".unserialize($row[$j])->skill." ".unserialize($row[$j])->year."</div></div>";
                                   }
                                 }
                               }
                             }
                             ?>
                           </div>
                           <div class = "viewexperiencetitle" >
                             <div style = "font-size: 35px; padding: 0% 3%">Experience</div>
                             <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
                           </div>
                           <div class = "viewexperience">
                             <?php
                             $exp = $conn->query("SELECT Email, Experience1, Experience2, Experience3, Experience4, Experience5 FROM user_login");
                             while($row = $exp -> fetch_array(MYSQLI_NUM)) {
                               if($row[0] == $email) {
                                 for ($j = 1; $j < 6; $j++) {
                                   if (is_null($row[$j]) or empty($row[$j]) or $row[$j] == "NULL") {

                                     continue;
                                   }
                                   else {
                                     echo "<div class = experiencetag><div class = experiencerole>".unserialize($row[$j])->role."</div><div class = experiencestartend>".unserialize($row[$j])->start." - ".unserialize($row[$j])->end."</div><div class = experiencecompany>".unserialize($row[$j])->company."</div><div class = experiencedescription>".unserialize($row[$j])->description."</div></div>";
                                   }
                                 }
                               }
                             }
                             ?>
                           </div>
                           <div class = "viewawardstitle" >
                             <div style = "font-size: 35px; padding: 0% 3%">Accomplishments</div>
                             <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
                           </div>
                           <div class = "viewawards">
                             <?php
                             $awrd = $conn->query("SELECT Email, Award1, Award2, Award3, Award4, Award5, Award6, Award7 FROM user_login");
                             while($row = $awrd -> fetch_array(MYSQLI_NUM)) {
                               if($row[0] == $email) {
                                 for ($j = 1; $j < 8; $j++) {
                                   if (is_null($row[$j]) or empty($row[$j]) or $row[$j] == "NULL") {
                                     continue;
                                   }
                                   else {
                                     echo "<div class = awardtag><div class = eachaward>".$row[$j]."</div></div>";
                                   }
                                 }
                               }
                             }
                             ?>
                           </div>
                           <div class = "vieweducationtitle">
                             <div style = "font-size: 35px; padding: 0% 3%">Education</div>
                             <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
                           </div>
                           <div class = "vieweducation">
                             <?php
                             $edu = $conn->query("SELECT Email, Education1, Education2, Education3 FROM user_login");
                             while($row = $edu -> fetch_array(MYSQLI_NUM)) {

                               if($row[0] == $email) {
                                 for ($j = 1; $j < 4; $j++) {
                                   if (is_null($row[$j]) or empty($row[$j]) or $row[$j] == "NULL") {

                                     continue;
                                   }
                                   else {
                                     if (unserialize($row[$j])->year == '7') {
                                       $year = 'Graduated';
                                       if (isset(unserialize($row[$j])->gpa)) {
                                         $year = 'Graduated | GPA: '.unserialize($row[$j])->gpa;
                                       }
                                     }
                                     else if (unserialize($row[$j])->year == '0') {
                                       $year = '';
                                       if (isset(unserialize($row[$j])->gpa)) {
                                         $year = 'GPA: '.unserialize($row[$j])->gpa;
                                       }
                                     }
                                     else {
                                       $year = 'Year '.unserialize($row[$j])->year;
                                       if (isset(unserialize($row[$j])->gpa)) {
                                         $year = 'Year '.unserialize($row[$j])->year.'| GPA: '.unserialize($row[$j])->gpa;
                                       }
                                     }
                                     if (unserialize($row[$j])->level == '1') {
                                       $program = 'High School Diploma';
                                     }
                                     if (unserialize($row[$j])->level == '2') {
                                       $program = 'Associate of '.unserialize($row[$j])->program;
                                     }
                                     if (unserialize($row[$j])->level == '3') {
                                       $program = 'Bachelor of '.unserialize($row[$j])->program;
                                     }
                                     if (unserialize($row[$j])->level == '4') {
                                       $program = 'Master of '.unserialize($row[$j])->program;
                                     }
                                     if (unserialize($row[$j])->level == '5') {
                                       $program = 'Doctorate of '.unserialize($row[$j])->program;
                                     }
                                     echo "<div class = educationtag><div class = educationschool>".unserialize($row[$j])->school."</div><div class = educationyear>".$year."</div><div class = educationprogram>".$program."</div><div class = educationdescription>".unserialize($row[$j])->description."</div></div>";
                                   }
                                 }
                               }
                             }
                             ?>
                           </div>
                         </div> <?php
                         break;
                         echo "</div>";
                       }
                     }
                   }
                 }
               }
               else {
                 echo "<div class = 'noapplicants'>No Applicants</div>";
               }
                ?>
              </div>
              <?php
            }
          }
          break;
        }
      }?>
      </div>
      <script>
      function showprofile(email) {
        var profile = document.getElementById("viewresumecontents"+email);
        var button = document.getElementById(email+"profile");
        if (profile.style.display == "block") {
          profile.style.display = "none";
          button.innerHTML = 'View Resume';
        }
        else {
          profile.style.display = "block";
          button.innerHTML = 'Hide Resume';
        }
      }
      function showcv(email) {
        var cv = document.getElementById("viewcvcontents"+email);
        var button = document.getElementById(email+"cv");
        if (cv.style.display == "block") {
          cv.style.display = "none";
          button.innerHTML = 'View Cover Letter';
        }
        else {
          cv.style.display = "block";
          button.innerHTML = 'Hide Cover Letter';
        }
      }
      </script>
    </div>
  </body>
</html>
