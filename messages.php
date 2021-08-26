<!DOCTYPE html>
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
      break;
    }
  }
  $sentapplications = "SELECT * FROM sent_application WHERE Email = `$username`";

  $exists = $conn->query($sentapplications);
  if (is_null($exists)) {
    $insert = $conn->prepare("INSERT INTO sent_application (Email) VALUES (?)");
    $insert->bind_param("s", $username);
    if ($insert->execute()) {
      echo "Email added";
    }
  }
  $applicationarray = array();
  $sentapplications = "SELECT * FROM sent_application WHERE Email = '$username'";


  $exists = $conn->query($sentapplications);
  $numfields = $exists->field_count;

  $numapps = 0;
  $row = $exists->fetch_array(MYSQLI_NUM);
  for ($i = 1; $i < $numfields; $i++) {
    if (!empty($row[$i]) && $row[$i] != "NULL") {
      array_push($applicationarray, $row[$i]);
      $numapps++;
    }
  }
  if ($numapps > 0) {
    $cv = unserialize($applicationarray[$numapps - 1])->cv;
  }
} ?>

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
    <?php
    if (!isset($_GET['page'])) {
      $page = 1;
    }
    else {
      $page = $_GET['page'];
    }
     ?>
    <script>
    window.history.replaceState("NULL", "Messages", 'messages.php?page=<?php echo $page; ?>');
    var href = String(window.location.href);
    var postingnumber = -1;
    // console.log(href.indexOf('view-posting'));
    if (href.indexOf('view-posting') != -1) {
      var openposting = true;
      var postingnumber = Number(href.slice(href.indexOf('view-posting') + 12));
      // console.log(postingnumber);
    }
    </script>
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
        <a class = "active" href="messages.php"><div id = "editprofile" class = "active">Postings</div></a>
        <a href="sentpostings.php"><div id = "viewprofile">Postings Sent</div></a>
        <a  href="sentapplications.php"><div id = "viewprofile">Applications Sent</div></a>
      </div>
    </div>
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
        $position = strpos($page, 'view-posting');
        if ($position) {
          $page = substr($page, 0, $position);
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
            $(this).append("<td <?php if ($page == $i) { echo "class = 'active'"; }?>><a style = 'padding: 20px;' href = '?page=<?php echo $i; ?>' onclick = 'changeURL();'><?php echo $i; ?></a></td>");
          });

          function changeURL() {
            window.history.replaceState("Messages", "Messages", "/JobSite/messages.php");
          }
          </script>
  <?php } ?>
      </div>

      <div class = "viewpostingdetails" id = "viewpostingdetails">
<?php for ($i = $pageresult; $i < $pageresult + smaller(3, $postingcount - $pageresult); $i++) { ?>
        <div class = "viewpostingscard" id = "viewpostingscard<?php echo $i; ?>"  style = "display: none;">
          <form action="sendapplication.php" name = "sendapplicationform<?php echo $i; ?>" method = "POST" enctype='multipart/form-data' id = "sendapplicationform">
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
            <div class = "applybuttonwrapper">
              <div class = "applybutton">
                <input type = "button" class = "applytoposting" value = "Apply" onclick = "showsendresume('<?php echo $i; ?>');">
              </div>
            </div>
            <div class = "applystepone" style = "display: none" id = "applystepone<?php echo $i; ?>">
              <div style = "font-size: 35px; padding: 0% 3%; margin-top: 15px">Step 1: Resume</div>
              <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
            </div>
            <div class = "sendresume" style = "display: none" id = "sendresume<?php echo $i; ?>">
              <div class = "uploadresumecontainer">
                <input id = "uploadresume" type = "button" name = "useprofile<?php echo $i; ?>" value = "Use Profile" onclick = "showprofile('<?php echo $i; ?>');">
              </div>
              <div class = "separator">&nbsp;</div>
              <!-- <input id = "uploadresume" type = "button" value = "Upload Resume" onclick = "uploadprofile('<?php echo $i; ?>');"> -->
              <div class = "uploadresumecontainer">
                <label for="resumeupload<?php echo $i; ?>" class="resumeupload">
                  <i class="fa fa-cloud-upload" class = "resumeupload"></i> Upload Resume
                </label>
                <input id = "resumeupload<?php echo $i; ?>" type="file" name = "resume<?php echo $i; ?>" />
              </div>
            </div>
            <div class = "viewresumecontents" id = viewresumecontents<?php echo $i;?> style = "display: none;">
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
            </div>
            <div class = "resumeuploadfail" id = "resumeuploadfail<?php echo $i; ?>" style = "display: none;">Invalid File Type</div>
            <div class = "resumeuploadsuccess" id = "resumeuploadsuccess<?php echo $i; ?>" style = "display: none;">Valid File Type</div>
            <div class = "applysteptwo" style = "display: none" id = "applysteptwo<?php echo $i; ?>">
              <div style = "font-size: 35px; padding: 0% 3%; margin-top: 15px">Step 2: Cover Letter</div>
              <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
            </div>
            <div class = "sendresume" id = "sendcoverletter<?php echo $i; ?>" style = "display: none;">
              <div class = "uploadresumecontainer">
                <input id = "uploadresume" type = "button" value = "Write CV" onclick = "showtextarea('<?php echo $i; ?>');">
              </div>
              <div class = "separator">&nbsp;</div>
              <!-- <input id = "uploadresume" type = "button" value = "Upload Resume" onclick = "uploadprofile('<?php echo $i; ?>');"> -->
              <div class = "uploadresumecontainer">
                <label for="cvupload<?php echo $i; ?>" class="resumeupload">
                  <i class="fa fa-cloud-upload" class = "resumeupload"></i> Upload CV
                </label>
                <input id = "cvupload<?php echo $i; ?>" type="file" name = "cv<?php echo $i; ?>" />
              </div>
            </div>
            <div class = "writecv" id = "writecv<?php echo $i; ?>"style = "display: none">
              <textarea class = writecvtextarea name = cvtext<?php echo $i; ?> id = "writecv<?php echo $i; ?>"><?php print isset($cv) ? $cv : ''; ?></textarea>
            </div>
            <div class = "resumeuploadfail" id = "cvuploadfail<?php echo $i; ?>" style = "display: none;">Invalid File Type</div>
            <div class = "resumeuploadsuccess" id = "cvuploadsuccess<?php echo $i; ?>" style = "display: none;">Valid File Type</div>
            <div class = "applystepthree" style = "display: none" id = "applystepthree<?php echo $i; ?>">
              <div style = "font-size: 35px; padding: 0% 3%; margin-top: 15px">Step 3: Additional Documents</div>
              <hr style = "margin-left: 10px; width: 90%; margin-bottom: 15px; margin-top: -2px" color = "darkblue" size = "3">
            </div>
            <div class = "additionaldocuments" id = "additionaldocuments<?php echo $i; ?>" style = "display: none">
              <div class = "websitelink"><input type = "url" name = "weblink<?php echo $i; ?>" placeholder = "Link to website, LinkedIn, portfolio, etc."></div>
              <div class = "transcriptfile">
                <label for="transcriptupload<?php echo $i; ?>" class="resumeupload">
                  <i class="fa fa-cloud-upload" class = "resumeupload"></i> Upload Additional Documents
                </label>
                <input id = "transcriptupload<?php echo $i; ?>" type="file" name = "transcript<?php echo $i; ?>" />
              </div>
              <div class = "resumeuploadfail" id = "transcriptuploadfail<?php echo $i; ?>" style = "display: none;">Invalid File Type</div>
              <div class = "resumeuploadsuccess" id = "transcriptuploadsuccess<?php echo $i; ?>" style = "display: none;">Valid File Type</div>
              <div class = "sendapplication">
                <input type = "submit" id = "sendapplication" name = sendapplication<?php echo $i; ?> value = "Send Application">
              </div>
            </div>
          </form>
        </div>

  <?php } ?>
        <script>
        function showsendresume(index) {
          var sendindex = "sendresume" + index;
          var steponeindex = "applystepone" + index;

          var uploadresume = document.getElementById(sendindex);
          uploadresume.style.display = 'block';
          var stepone = document.getElementById(steponeindex);
          stepone.style.display = 'block';
        }
        function showprofile(index) {
          var resumeindex = "viewresumecontents" + index;
          var profile = document.getElementById(resumeindex);
          profile.style.display = 'block';
          document.getElementById("resumeuploadfail"+index).style.display = 'none';
          document.getElementById("resumeuploadsuccess"+index).style.display = 'none';

          var steptwoindex = "applysteptwo" + index;
          var steptwo = document.getElementById(steptwoindex);

          steptwo.style.display = 'block';
          document.getElementById("resumeupload"+index).value = "";
          document.getElementById("sendcoverletter" + index).style.display = 'block';
        }
        function showtextarea(index) {
          var cvindex = "writecv" + index;
          var cvtextarea = document.getElementById(cvindex);
          document.getElementById("cvuploadfail"+index).style.display = 'none';
          document.getElementById("cvuploadsuccess"+index).style.display = 'none';

          cvtextarea.style.display = 'block';
          var cvid = "cvupload"+index;
          document.getElementById(cvid).value = "";
          $(".writecvtextarea").on('change', function() {
            document.getElementById("applystepthree"+index).style.display = 'block';
            document.getElementById("additionaldocuments"+index).style.display = 'block';
            <?php $_SESSION['index'] = substr($_SERVER['REQUEST_URI'], -1); ?>
          });
        }

        if (postingnumber > -1) {
          var posting = document.getElementById("viewpostingscard"+postingnumber);
          posting.style.display = 'block';
        }

        window.onpopstate = function(e){
          if(e.state){
            document.title = e.state.pageTitle;
          }
        };

        function showposting(id) {

          var page = Math.ceil((Number(id) + 1) / 3);

          var postingindex = id;
          var posting = document.getElementById("viewpostingscard"+id);
          // console.log(posting);
          $('.viewpostingscard').each(function(i, obj) {
            obj.style.display = 'none';
          });
          posting.style.display = 'block';

          var stepone = document.getElementById("applystepone"+id);
          if (stepone != null) {
            stepone.style.display = 'none';
          }

          var sendindex = "sendresume" + id;
          var uploadresume = document.getElementById(sendindex);
          uploadresume.style.display = 'none';
          var profile = document.getElementById("viewresumecontents" + id);
          profile.style.display = 'none';
          window.history.pushState("Show Posting", "View Posting " + id, "/JobSite/messages.php?page="+page+"view-posting"+id);
        }


        const extensions = ["doc","docx","pdf","txt", "html"];
        var invalidfile = false;
        for (var i = <?php echo $pageresult;?>; i < <?php echo $pageresult + smaller(3, $postingcount - $pageresult);?>; i++) {

          var resume = document.getElementById("resumeupload"+i);
          var cv = document.getElementById("cvupload"+i);
          var transcript = document.getElementById("transcriptupload"+i);
          // console.log(resume);

          resume.addEventListener('change', function () {
            var url = window.location.href;
            var index = url.slice(url.length - 1);
            var resumeindex = "viewresumecontents" + index;
            document.getElementById(resumeindex).style.display = 'none';

            console.log(index);
            var resume = document.getElementById("resumeupload"+index);
            var fileextension = "";
            document.getElementById("resumeuploadfail"+index).style.display = 'none';
            document.getElementById("resumeuploadsuccess"+index).style.display = 'none';

            var filenamelength = resume.value.length;
            // console.log(filenamelength);
            for (var j = filenamelength - 1; j >= 0; j--) {
              if (resume.value.charAt(j) == '.') {
                break;
              }
              fileextension = resume.value.charAt(j) + fileextension;
              console.log(fileextension);
            }

            if (!(extensions.includes(fileextension))) {
              document.getElementById("resumeuploadfail"+index).style.display = 'block';
              document.getElementById("resumeuploadsuccess"+index).style.display = 'none';
            }
            else {
              document.getElementById("resumeuploadfail"+index).style.display = 'none';
              document.getElementById("resumeuploadsuccess"+index).style.display = 'block';
              document.getElementById("applystepone"+index).style.display = 'block';
              document.getElementById("applysteptwo"+index).style.display = 'block';
              document.getElementById("sendcoverletter"+index).style.display = 'block';
            }
          });

          cv.addEventListener('change', function () {
            var url = window.location.href;
            var index = url.slice(url.length - 1);
            var cvindex = "writecv" + index;
            document.getElementById(cvindex).style.display = 'none';

            // console.log(index);
            var cv = document.getElementById("cvupload"+index);
            var cvextension = "";
            document.getElementById("cvuploadfail"+index).style.display = 'none';
            document.getElementById("cvuploadsuccess"+index).style.display = 'none'; //where i left off
            var filenamelength = cv.value.length;
            for (var j = filenamelength - 1; j >= 0; j--) {
              if (cv.value.charAt(j) == '.') {
                break;
              }
              cvextension = cv.value.charAt(j) + cvextension;
              console.log(cvextension);
            }


            if (!(extensions.includes(cvextension))) {
              document.getElementById("cvuploadfail"+index).style.display = 'block';
              document.getElementById("cvuploadsuccess"+index).style.display = 'none';
            }
            else {
              document.getElementById("cvuploadfail"+index).style.display = 'none';
              document.getElementById("cvuploadsuccess"+index).style.display = 'block';
              document.getElementById("applystepthree"+index).style.display = 'block';
              document.getElementById("additionaldocuments"+index).style.display = 'block';

            }
          });
          transcript.addEventListener('change', function () {
            var url = window.location.href;
            var index = url.slice(url.length - 1);
            var divcontainer = document.getElementById("additionaldocuments"+index);


            var fileextension = "";
            document.getElementById("transcriptuploadfail"+index).style.display = 'none';
            document.getElementById("transcriptuploadsuccess"+index).style.display = 'none';
            divcontainer.style.height = '150px';

            var transcript = document.getElementById("transcriptupload"+index);
            var filenamelength = transcript.value.length;
            // console.log(filenamelength);
            for (var j = filenamelength - 1; j >= 0; j--) {
              if (transcript.value.charAt(j) == '.') {
                break;
              }
              fileextension = transcript.value.charAt(j) + fileextension;
              console.log(fileextension);
            }

            if (!(extensions.includes(fileextension))) {
              document.getElementById("transcriptuploadfail"+index).style.display = 'block';
              document.getElementById("transcriptuploadsuccess"+index).style.display = 'none';
              divcontainer.style.height = '220px';

            }
            else {
              document.getElementById("transcriptuploadfail"+index).style.display = 'none';
              document.getElementById("transcriptuploadsuccess"+index).style.display = 'block';
              divcontainer.style.height = '220px';
            }
          });
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
