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
      $numcolumns = $result->field_count;
      while ($row = $result->fetch_array(MYSQLI_NUM)) {


        if ($row[0] == $username) {
          if (isset($_GET['editerror'])) { ?>
            <p class = "editerror" style = "text-align: center"><?php echo $_GET['editerror']; ?></p>
            <script>
            $(document).ready(function() {
                $(".editerror").delay(3000).fadeOut(1000);
            });
            </script>
          <?php }
          else if (isset($_GET['editsuccess'])) { ?>
            <p class = "editsuccess" style = "text-align: center"><?php echo $_GET['editsuccess']; ?></p>
            <script>
            $(document).ready(function() {
                $(".editsuccess").delay(3000).fadeOut(1000);
            });
            </script>
          <?php }
          else { ?>
            <div style = "height: 55px">&nbsp;</div>
          <?php }
          for ($i = 1; $i < $numcolumns; $i++) {
            // echo $row[$i];
            if ($row[$i] != "NULL" and !is_null($row[$i]) and !empty($row[$i])) {
              ?>
              <div class = "eachsentposting" id = "sentposting<?php echo $i;?>">
                <div class = "eachsentpostingheader">
                  <div class = "eachsentpostingheaderinfo">
                    <div class = "sentpostingtitle">
                      <?php echo unserialize($row[$i])->position." at ".unserialize($row[$i])->company; ?>
                    </div>
                    <div class = eachsentpostingdetails>
                      <?php
                      if (isset(unserialize($row[$i])->remote)) {
                        if (unserialize($row[$i])->remote == 1) {
                          $type = "Remote";
                        }
                        else if (unserialize($row[$i])->remote == 2) {
                          $type = "Temporarily Remote";
                        }
                        else {
                          $type = "In Person";
                        }
                        echo "<div class = ispostingremote><div class = postingremote>".$type."</div></div>";
                      }

                      if (isset(unserialize($row[$i])->type)) {
                        echo "<div class = 'ispostingremote'><div class = 'postingremote'>".unserialize($row[$i])->type."</div></div>";
                      }
                      echo "<div class = 'ispostingremote'><div class = 'postingremote'>Date Posted: ".preg_split('/\s+/', unserialize($row[$i])->dateposted)[0]."</div></div><div class = ispostingremote><div class = postingremote>Id: ".unserialize($row[$i])->id."</div></div>";
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
                  <div class = "postingeditdelete">
                    <div class = "editposting">
                      <button type = "button" class = postingedit onclick = "editposting('<?php echo $i; ?>');">Edit</button>
                    </div>
                    <form action = "deleteposting.php" method = "POST">
                      <input type = "text" name = index value = '<?php echo $i;?>' style = "display: none">
                      <input type = "text" name = postingid<?php echo $i; ?> value = '<?php echo unserialize($row[$i])->id;?>' style = "display: none">
                      <input type = "text" name = previouspostingname<?php echo $i; ?> value = '<?php echo unserialize($row[$i])->position." ".unserialize($row[$i])->company." ".unserialize($row[$i])->id; ?>' style = "display: none">
                      <div class = "deleteposting">
                        <button type = "submit" name = "deleteposting" class = postingdelete>Delete</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class = "editpostingcontainer" id = editpostingcontainer<?php echo $i;?> style = "display: none">
                <form action="editposting.php" method = "POST" class = "sendmessage"> <!--job posting form-->
                  <div class = "editpostinginfo">
                    <div class = "positionrole">
                      <input type = "text" name = "positionrole<?php echo $i; ?>" id = "positionrole" placeholder = "Position" required = "required" value = "<?php print isset(unserialize($row[$i])->position) ? unserialize($row[$i])->position : ''; ?>">
                    </div>
                    <div class = "separator">&nbsp;</div>
                    <div class = "companyinfo">
                      <input type = "text" name = "companyinfo<?php echo $i; ?>" id = "companyinfo" placeholder = "Company" required = "required" value = "<?php print isset(unserialize($row[$i])->company) ? unserialize($row[$i])->company : ''; ?>">
                    </div>
                    <div class = "separator">&nbsp;</div>
                    <div class = "typeinfo">
                      <?php
                      $rectype = unserialize($row[$i])->type;
                        if (is_null($rectype) or empty($rectype)) {
                      ?>
                      <select id="rectype" name="positiontype<?php echo $i; ?>" required>
                        <option value="" disabled selected>Select</option>
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Casual">Casual</option>
                        <option value="Temporary">Temporary</option>
                        <option value="Seasonal">Seasonal</option>
                        <option value="Internship">Internship</option>
                        <option value="Contract">Contract</option>
                      </select>
                      <?php } else {?>
                      <select id="rectype" name="positiontype<?php echo $i; ?>" required>
                        <option value=""<?php if ($rectype== ""): ?> selected="selected"<?php endif; ?>>Select</option>
                        <option value="Full Time"<?php if ($rectype== "Full Time"): ?> selected="selected"<?php endif; ?>>Full Time</option>
                        <option value="Part Time"<?php if ($rectype== "Part Time"): ?> selected="selected"<?php endif; ?>>Part Time</option>
                        <option value="Casual"<?php if ($rectype== "Casual"): ?> selected="selected"<?php endif; ?>>Casual</option>
                        <option value="Temporary"<?php if ($rectype== "Temporary"): ?> selected="selected"<?php endif; ?>>Temporary</option>
                        <option value="Seasonal"<?php if ($rectype== "Seasonal"): ?> selected="selected"<?php endif; ?>>Seasonal</option>
                        <option value="Internship"<?php if ($rectype== "Internship"): ?> selected="selected"<?php endif; ?>>Internship</option>
                        <option value="Contract"<?php if ($rectype== "Contract"): ?> selected="selected"<?php endif; ?>>Contract</option>
                      </select>
                      <?php } ?>
                    </div>
                  <!-- </div> -->
                  <!-- <div class = "salaryrange"> -->
                    <div class = "salarystart">
                      <input type = "text" name = "salarystart<?php echo $i; ?>" id = "salarystart" placeholder = "Min Salary" required value = "<?php print isset(unserialize($row[$i])->salarystart) ? unserialize($row[$i])->salarystart : ''; ?>">
                    </div>
                    <div class = "separator">&nbsp;</div>
                    <div class = "salaryend">
                      <input type = "text" name = "salaryend<?php echo $i; ?>" id = "salaryend" placeholder = "Max Salary" value = "<?php print isset(unserialize($row[$i])->salaryend) ? unserialize($row[$i])->salaryend : ''; ?>">
                    </div>
                    <div class = "separator">&nbsp;</div>
                    <div class = currency style = "height: 30px; text-align: center; margin-top: 7px; display: inline-block; width: 25px">Or</div>
                    <div class = "separator">&nbsp;</div>
                    <div class = "salarystart">
                      <input type = "text" name = "hourlywage<?php echo $i; ?>" id = "wage" placeholder = "Hourly Wage" required value = "<?php print isset(unserialize($row[$i])->wage) ? unserialize($row[$i])->wage : ''; ?>">
                    </div>
                    <div class = "separator">&nbsp;</div>
                    <div class = "currency">
                      <?php if (!property_exists(unserialize($row[$i]), 'currency') or unserialize($row[$i])->currency == "") { ?>
                      <select id="currency" name="currency<?php echo $i; ?>" required = "required">
                        <option value="" selected disabled>Currency</option>
                        <option value="USD">USD</option>
                        <option value="CAD">CAD</option>
                        <option value="EUR">EUR</option>
                      </select>
                    <?php } else { ?>
                      <select id="currency" name="currency<?php echo $i; ?>" required = "required">
                        <option value="NULL" selected disabled>Currency</option>
                        <option value="USD"<?php if (unserialize($row[$i])->currency == "USD"): ?> selected="selected"<?php endif; ?>>USD</option>
                        <option value="CAD"<?php if (unserialize($row[$i])->currency == "CAD"): ?> selected="selected"<?php endif; ?>>CAD</option>
                        <option value="EUR"<?php if (unserialize($row[$i])->currency == "EUR"): ?> selected="selected"<?php endif; ?>>EUR</option>
                      </select>
                    <?php } ?>
                    </div>
                    <div class = "postinglocationinfo" style = "display: inline-block; width: 100%">

                      <div class = "location">Remote:
                        <?php
                        $recremote = unserialize($row[$i])->remote;
                        if ($recremote == NULL) {
                         ?>
                        <select id="remote<?php echo $i;?>" name="recremote<?php echo $i; ?>" required>
                          <option value="" disabled selected>Remote?</option>
                          <option value="1">Yes</option>
                          <option value="2">Temporarily</option>
                          <option value="3">No</option>
                        </select>
                          <?php
                        } else {
                           ?>
                        <select id="remote<?php echo $i;?>" name="recremote<?php echo $i; ?>">
                          <option value=""<?php if ($recremote == ""): ?> selected="selected"<?php endif; ?>>Remote?</option>
                          <option value="1"<?php if ($recremote == "1"): ?> selected="selected"<?php endif; ?>>Yes</option>
                          <option value="2"<?php if ($recremote == "2"): ?> selected="selected"<?php endif; ?>>Temporarily</option>
                          <option value="3"<?php if ($recremote == "3"): ?> selected="selected"<?php endif; ?>>No</option>
                        </select>
                       <?php } ?>
                     </div>
                     <div class = "separator">&nbsp;</div>

                      <div class = "reclocationinfo" id = "reclocationinfo<?php echo $i;?>">

                        <?php
                        $reccity = unserialize($row[$i])->city;
                        $recregion = unserialize($row[$i])->region;
                        $reccountry = unserialize($row[$i])->country; ?>
                        <div class = "location">
                          City / Town: <input type = "search" name = "reccity<?php echo $i; ?>" id = reccity placeholder = "City / Town" value="<?php print isset($reccity) ? $reccity : ''; if (isset($required) and $required == "True") {echo 'required';} else { echo '';}?>" >
                        </div>
                        <div class = "separator">&nbsp;</div>

                        <div class = "location">
                          State / Province: <input type = "search" name = "recregion<?php echo $i; ?>" id = recregion placeholder = "State / Province" value="<?php print isset($recregion) ? $recregion : ''; if (isset($required) and $required == "True") {echo 'required';} else { echo '';}?>">
                        </div>
                        <div class = "separator">&nbsp;</div>

                        <div class = "location">
                          Country: <input type = "search" name = "reccountry<?php echo $i; ?>" id = reccountry placeholder = "Country" value="<?php print isset($reccountry) ? $reccountry : ''; if (isset($required) and $required == "True") {echo 'required';} else { echo '';}?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class = "editpostingmessage">
                    <textarea type = "text" id = "writemessage" name = "writemessage<?php echo $i; ?>" placeholder = "Write a job posting you want to send to users" required = "required"><?php print isset(unserialize($row[$i])->message) ? unserialize($row[$i])->message : ''; ?></textarea>
                  </div>
                  <input type = "text" name = index value = '<?php echo $i;?>' style = "display: none">
                  <input type = "text" name = postingid<?php echo $i; ?> value = '<?php echo unserialize($row[$i])->id;?>' style = "display: none">
                  <input type = "text" name = previouspostingname<?php echo $i; ?> value = '<?php echo unserialize($row[$i])->position." ".unserialize($row[$i])->company." ".unserialize($row[$i])->id; ?>' style = "display: none">
                  <div class = "savepostingedit">
                    <input id = "savepostingedit" type="submit" value="Save Changes" name="saveedits" onclick = "submitted('<?php echo $i; ?>')">
                  </div>
                  <br style="clear: both" />
                </form>
              </div>
              <div class = "applicantslist">
               <?php
               $postingname = unserialize($row[$i])->position." ".unserialize($row[$i])->company." ".unserialize($row[$i])->id;
               $select = "SELECT Email, `$postingname` FROM sent_application";
               $result = $conn->query($select);
               if ($result) {
                 while ($applicants = $result->fetch_array(MYSQLI_ASSOC)) {
                   if ($applicants[$postingname] != "NULL" and !empty($applicants[$postingname])) {
                     $applicantemail = $applicants['Email'];
                     $cvname = "cv ".$applicantemail." - ".unserialize($row[$i])->position." ".unserialize($row[$i])->id;

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
                           echo "<button class = applicantresume id = '".$applicantemail."profile' onclick = showprofile('".$applicantemail.$i."')>View Resume</button>";
                         }
                         else {
                           echo "<a href = '".unserialize($applicants[$postingname])->resume."' download><button class = applicantresume>Download Resume</button></a>";
                         }
                         if (strpos(unserialize($applicants[$postingname])->cv, "coverletter/".$applicantemail."/".$cvname) !== false) {
                           echo "<a href = '".unserialize($applicants[$postingname])->cv."' download><button class = applicantresume>Download Cover Letter</button></a>";
                         }
                         else {
                           echo "<button class = applicantresume id = '".$applicantemail."cv' onclick = showcv('".$applicantemail.$i."')>View Cover Letter</button>";
                         }
                         if (isset(unserialize($applicants[$postingname])->weblink) and !empty(unserialize($applicants[$postingname])->weblink)) {
                           echo "<a href = '".unserialize($applicants[$postingname])->weblink."'><button class = applicantresume>Web Link</button></a>";
                         }
                         if (isset(unserialize($applicants[$postingname])->otherfile) and unserialize($applicants[$postingname])->otherfile != "NULL") {
                           echo "<a href = '".unserialize($applicants[$postingname])->otherfile."' download><button class = applicantresume>Web Link</button></a>";
                         }
                         echo "</div></div>";
                         ?>
                         <div class = "viewresumecontents" id = viewcvcontents<?php echo $email.$i;?> style = "display: none">
                           <?php echo unserialize($applicants[$postingname])->cv;?>
                         </div>
                         <div class = "viewresumecontents" id = viewresumecontents<?php echo $email.$i;?> style = "display: none;">
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
                             while($skills = $skll -> fetch_array(MYSQLI_NUM)) {
                               if($skills[0] == $email) {
                                 for ($j = 1; $j < 8; $j++) {
                                   if (is_null($skills[$j]) or empty($skills[$j]) or $skills[$j] == "NULL") {
                                     continue;
                                   }
                                   else {
                                     echo "<div class = skilltag><div class = eachskill>".unserialize($skills[$j])->skill." ".unserialize($skills[$j])->year."</div></div>";
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
                             while($experience = $exp -> fetch_array(MYSQLI_NUM)) {
                               if($experience[0] == $email) {
                                 for ($j = 1; $j < 6; $j++) {
                                   if (is_null($experience[$j]) or empty($experience[$j]) or $experience[$j] == "NULL") {

                                     continue;
                                   }
                                   else {
                                     echo "<div class = experiencetag><div class = experiencerole>".unserialize($experience[$j])->role."</div><div class = experiencestartend>".unserialize($experience[$j])->start." - ".unserialize($experience[$j])->end."</div><div class = experiencecompany>".unserialize($experience[$j])->company."</div><div class = experiencedescription>".unserialize($experience[$j])->description."</div></div>";
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
                             while($award = $awrd -> fetch_array(MYSQLI_NUM)) {
                               if($award[0] == $email) {
                                 for ($j = 1; $j < 8; $j++) {
                                   if (is_null($award[$j]) or empty($award[$j]) or $award[$j] == "NULL") {
                                     continue;
                                   }
                                   else {
                                     echo "<div class = awardtag><div class = eachaward>".$award[$j]."</div></div>";
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
                             while($education = $edu -> fetch_array(MYSQLI_NUM)) {

                               if($education[0] == $email) {
                                 for ($j = 1; $j < 4; $j++) {
                                   if (is_null($education[$j]) or empty($education[$j]) or $education[$j] == "NULL") {

                                     continue;
                                   }
                                   else {
                                     if (unserialize($education[$j])->year == '7') {
                                       $year = 'Graduated';
                                       if (isset(unserialize($education[$j])->gpa)) {
                                         $year = 'Graduated | GPA: '.unserialize($row[$j])->gpa;
                                       }
                                     }
                                     else if (unserialize($education[$j])->year == '0') {
                                       $year = '';
                                       if (isset(unserialize($education[$j])->gpa)) {
                                         $year = 'GPA: '.unserialize($education[$j])->gpa;
                                       }
                                     }
                                     else {
                                       $year = 'Year '.unserialize($education[$j])->year;
                                       if (isset(unserialize($education[$j])->gpa)) {
                                         $year = 'Year '.unserialize($education[$j])->year.'| GPA: '.unserialize($education[$j])->gpa;
                                       }
                                     }
                                     if (unserialize($education[$j])->level == '1') {
                                       $program = 'High School Diploma';
                                     }
                                     if (unserialize($education[$j])->level == '2') {
                                       $program = 'Associate of '.unserialize($education[$j])->program;
                                     }
                                     if (unserialize($education[$j])->level == '3') {
                                       $program = 'Bachelor of '.unserialize($education[$j])->program;
                                     }
                                     if (unserialize($education[$j])->level == '4') {
                                       $program = 'Master of '.unserialize($education[$j])->program;
                                     }
                                     if (unserialize($education[$j])->level == '5') {
                                       $program = 'Doctorate of '.unserialize($education[$j])->program;
                                     }
                                     echo "<div class = educationtag><div class = educationschool>".unserialize($education[$j])->school."</div><div class = educationyear>".$year."</div><div class = educationprogram>".$program."</div><div class = educationdescription>".unserialize($education[$j])->description."</div></div>";
                                   }
                                 }
                               }
                             }
                             ?>
                           </div>
                         </div> <?php
                         echo "</div>";
                         break;

                       }
                     }
                   }
                 }
               }
               else {
                 echo "<div class = 'noapplicants'>No Applicants</div>";
               }
                ?>
              </div class = "applicantslist">
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

      function editposting(index) {
        var container = document.getElementById("editpostingcontainer" + index);
        if (container.style.display == 'none') {
          container.style.display = 'block';
          window.history.pushState("Edit Posting", "Edit Posting " + index, "/JobSite/sentpostings.php?edit-posting=" + index);
          // console.log(index);
        }
        else {
          container.style.display = 'none';
          window.history.back();

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
      function submitted(index) {
        $.ajax({
         method: 'POST',
         data: {'index' : index},
         url: 'localhost/JobSite/editposting.php',
         cache:  true,
         success: function(data) {console.log(index);},
         error: function() {console.log("error")}
       });
      }

      </script>
    </div>
  </body>
</html>
