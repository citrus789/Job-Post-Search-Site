<?php
session_start();
if(isset($_SESSION["Email"]) || $_SESSION['loggedin'] == true) {
  $username = $_SESSION['Email'];
  $email = $_SESSION['Email'];

  $conn = new mysqli("localhost","root", "", "website");
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


}?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset = "UTF-8">
    <meta name = "description" content = "This is my first experimental website">

    <title>First Website</title>
    <link rel = "stylesheet" href = "styles.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel = "stylesheet" href = "styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
  </head>
  <body>
    <header>

      <a href="home.html"><button class = "homebutton">Home</button></a>
      <nav>
        <ul class = "topnavlinks">
          <li><a href="search.php">Search</a></li>
          <li><a class="active" href="messages.php">Messages</a></li>
          <li><a href="editprofile.php">Profile</a></li>
        </ul>
      </nav>
      <a href="logout.php"><button class = "logoutbutton">Logout</button></a>

    </header>
    <div class = "postingscontainer">
      <div class = "postingslist">
        <h1 class = "postingstitle" style = "margin-left: 10px;">Your Postings</h1>
        <?php
        function smaller($a, $b) {
          if ($a < $b) {
            return $a;
          }
          return $b;
        }

        function objecttoarray($data) {
          if(is_array($data) || is_object($data)) {
            $result = array();
            foreach($data as $key => $value) {
              $result[$key] = $this->object_to_array($value);
            }
            return $result;
          }
          return $data;
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

        $numpages = ceil($postingcount / 3);
        if (!isset($_GET['page'])) {
          $page = 1;
        }
        else {
          $page = $_GET['page'];
        }
        $pageresult = ($page - 1) * 3;


        //echo $postingcount;
        for ($i = $pageresult; $i < $pageresult + smaller(3, $postingcount - $pageresult); $i++) { ?>

          <div class = "postingcard">
            <div style = "width: 100%">
              <div class = "maintitle">
                <div class = "postingtitle"><?php echo unserialize($postinglist[$i])->position." at ".unserialize($postinglist[$i])->company;?></div>
              </div>
              <div class = postingcardinfo>
                <div class = "moreinfo">
                  <div class = "salary">
                    <?php
                    if (!empty(unserialize($postinglist[$i])->salarystart)) {
                      echo "$".unserialize($postinglist[$i])->salarystart;
                      if (!empty(unserialize($postinglist[$i])->salaryend)) {
                        echo " - $".unserialize($postinglist[$i])->salaryend;
                      }
                    }
                    else {
                      echo "$".unserialize($postinglist[$i])->wage." / hour";
                    }
                    echo " (".unserialize($postinglist[$i])->currency.")";
                    ?>
                  </div>
                </div>
                <div class = "isremote">
                  <div class = "remote?"><?php echo "".unserialize($postinglist[$i])->remote.""?></div>
                </div>
                <?php
                if (!isset(unserialize($postinglist[$i])->type)) {
                  $type = '';
                }
                else {
                  $type = unserialize($postinglist[$i])->type;
                }

                ?>
                <div class = "isremote">
                  <div class = "remote?"><?php echo "".$type.""?></div>
                </div>
                <div class = "isremote">
                  <div class = "remote?"><?php if (unserialize($postinglist[$i])->remote == 'In Person') { echo "".unserialize($postinglist[$i])->city.", ".unserialize($postinglist[$i])->region.", ".unserialize($postinglist[$i])->country; }?></div>
                </div>
              </div>
              <div class = "viewposting" style = "margin-top: 180px; padding: 20px; height: 35px">
                <input type = "button" value = "View Posting" style = "width = 80%" onclick = "showposting('<?php echo $i; ?>');">
              </div>
            </div>
          </div>
  <?php }
        if ($postingcount > 3) { ?>
          <table class = "searchnav" id = "searchnav" style = "height: 25px;">
            <tr></tr>
          </table>
  <?php }
        for ($i = 1; $i <= $numpages; $i++) { ?>
          <script>
          $("#searchnav").find('tr').each(function() {
            $(this).append("<td <?php if ($page == $i) { echo "class = 'active'"; }?>><a style = 'padding: 20px;' href = 'messages.php?page=<?php echo $i; ?>'><?php echo $i; ?></a></td>");
          });
          </script>
  <?php } ?>
      </div>

      <?php
      // for ($i = $pageresult; $i < $pageresult + smaller(3, $postingcount - $pageresult); $i++) {
      //  echo $i;
      // }
      ?>
      <div class = "viewpostingdetails" id = "viewpostingdetails">
<?php for ($i = $pageresult; $i < $pageresult + smaller(3, $postingcount - $pageresult); $i++) { ?>
        <div class = "viewpostingscard" id = "viewpostingscard<?php echo $i; ?>"  style = "display: none;">
          <div class = "viewpostingtitle">
        <?php echo unserialize($postinglist[$i])->position." at ".unserialize($postinglist[$i])->company; ?>
          </div>
          <div class = "moreinfo" style = "margin-left: 10px; font-size: 30px">
            <?php
            if (!empty(unserialize($postinglist[$i])->salarystart)) {
              echo "$".unserialize($postinglist[$i])->salarystart;
              if (!empty(unserialize($postinglist[$i])->salaryend)) {
                echo " - $".unserialize($postinglist[$i])->salaryend;
              }
            }
            else {
              echo "$".unserialize($postinglist[$i])->wage." / hour";
            }
            echo " (".unserialize($postinglist[$i])->currency.")";
            ?>
          </div>

          <div class = "isremote" style = "font-size: 30px">
            <div class = "remote?"><?php echo "".unserialize($postinglist[$i])->remote.""?></div>
          </div>
          <?php
          if (!isset(unserialize($postinglist[$i])->type)) {
            $type = '';
          }
          else {
            $type = unserialize($postinglist[$i])->type;
          } ?>

          <div class = "isremote" style = "font-size: 30px">
            <div class = "remote?"><?php echo "".$type.""?></div>
          </div>
          <div class = "isremote" style = "font-size: 30px">
            <div class = "remote?"><?php if (unserialize($postinglist[$i])->remote == 'In Person') { echo "".unserialize($postinglist[$i])->city.", ".unserialize($postinglist[$i])->region.", ".unserialize($postinglist[$i])->country; }?></div>
          </div>
          <div class = "postingdetails" style = "display: inline-block; float: left; margin-left: 20px; width: 80%">
            <h4>Job Description</h4>
            <?php echo nl2br("\n".unserialize($postinglist[$i])->message);?>
          </div>
          <div class = "applybutton">
            <input type = "button" class = "applytoposting" value = "Apply" onclick = "showsendresume('<?php echo $i; ?>');">
          </div>
          <div class = "sendresume" style = "display: none" id = "sendresume<?php echo $i; ?>">
            <input id = "uploadresume" type = "button" class = "active" value = "Use Profile" onclick = "showprofile('<?php echo $i; ?>');">
            <div class = "separator">&nbsp;</div>
            <input id = "uploadresume" type = "button" value = "Upload Resume">
          </div>
          <div class = "viewresumecontents" id = viewresumecontents<?php echo $i;?> style = "display: none;">
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
          </div>

        </div>

  <?php } ?>
        <script>
          function showsendresume(index) {
            var sendindex = "sendresume" + index;
            var uploadresume = document.getElementById(sendindex);
            uploadresume.style.display = 'block';
          }
          function showprofile(index) {
            var resumeindex = "viewresumecontents" + index;
            var profile = document.getElementById(resumeindex);
            profile.style.display = 'block';
          }

        function showposting(id) {
          var postingindex = id;
          var posting = document.getElementById("viewpostingscard"+id);
          console.log(posting);
          $('.viewpostingscard').each(function(i, obj) {
            obj.style.display = 'none';
          });
          posting.style.display = 'block';
          var sendindex = "sendresume" + id;
          var uploadresume = document.getElementById(sendindex);
          uploadresume.style.display = 'none';
          var profile = document.getElementById("viewresumecontents");
          profile.style.display = 'none';
        }
        </script>
      </div>
    </div>
  </body>
</html>
<?php
$result->close();
$conn->close();
?>
