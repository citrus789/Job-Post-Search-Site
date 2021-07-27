<?php
session_start();
if(isset($_SESSION["Email"]) || $_SESSION['loggedin'] == true) {
  $username = $_SESSION['Email'];

  $conn = new mysqli("localhost","root", "", "website");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $result = $conn-> query("SELECT * FROM position");


  while($row = $result -> fetch_array(MYSQLI_ASSOC)) {
    if($row["Email"] == $username) {
      $recposition = $row["Position"];
      $rectype = $row["Type"];
      $recskill1 = $row["Skill1"];
      $recskill2 = $row["Skill2"];
      $recskill3 = $row["Skill3"];
      $recskill4 = $row["Skill4"];
      $recskill5 = $row["Skill5"];
      $recdate = $row["Start"];
      $reclevel = $row["Level"];
      $recyear = $row["Year"];
      $recprog1 = $row["Program1"];
      $recprog2 = $row["Program2"];
      $recprog3 = $row["Program3"];
      $recgpa = $row["GPA"];
      $recremote = $row["Remote"];
      if ($recremote == 0) {
        $recaddress = $row["Address"];
        $reccity = $row["City"];
        $recregion = $row["Region"];
        $reccountry = $row["Country"];
      }

    }
  }

}
?>
<html>
  <head>
    <meta charset = "UTF-8">
    <meta name = "description" content = "This is my first experimental website">

    <title>First Website</title>
    <link rel = "stylesheet" href = "styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel = "stylesheet" href = "styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="topnav">
      <a class="active" href="search.html">Search</a>
      <a href="messages.html">Messages</a>
      <a href="editprofile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
    <div class = "searchcontainer">
      <form action="savesearch.php" method = "POST">
        <h3 class = "recjob">Position</h3>
        <table>
          <tr>
            <td>Position: </td>
            <td><input type = "search" name = "recposition" placeholder = "Position" value="<?php print isset($recposition) ? $recposition : ''; ?>"></td>
          </tr>
          <tr>
            <td>Type: </td>
            <td>
              <?php
                if (is_null($rectype) or empty($rectype)) {
              ?>
              <select id="rectype" name="rectype">
                <option value="NULL">Select</option>
                <option value="Full Time">Full Time</option>
                <option value="Part Time">Part Time</option>
                <option value="Casual">Casual</option>
                <option value="Temporary">Temporary</option>
                <option value="Seasonal">Seasonal</option>
                <option value="Internship">Internship</option>
                <option value="Contract">Contract</option>
              </select>
              <?php } else {?>
              <select id="rectype" name="rectype">
                <option value="NULL"<?php if ($rectype== "NULL"): ?> selected="selected"<?php endif; ?>>Select</option>
                <option value="Full Time"<?php if ($rectype== "Full Time"): ?> selected="selected"<?php endif; ?>>Full Time</option>
                <option value="Part Time"<?php if ($rectype== "Part Time"): ?> selected="selected"<?php endif; ?>>Part Time</option>
                <option value="Casual"<?php if ($rectype== "Casual"): ?> selected="selected"<?php endif; ?>>Casual</option>
                <option value="Temporary"<?php if ($rectype== "Temporary"): ?> selected="selected"<?php endif; ?>>Temporary</option>
                <option value="Seasonal"<?php if ($rectype== "Seasonal"): ?> selected="selected"<?php endif; ?>>Seasonal</option>
                <option value="Internship"<?php if ($rectype== "Internship"): ?> selected="selected"<?php endif; ?>>Internship</option>
                <option value="Contract"<?php if ($rectype== "Contract"): ?> selected="selected"<?php endif; ?>>Contract</option>
              </select>
              <?php } ?>
            </td>
          </tr>
          <tr class="blank_row"><td bgcolor="azure" colspan="3">&nbsp;</tr>
            <td>Top 5 Skills</td>
          </tr>
          <tr>
            <td>Skill 1: </td>
            <td><input type = "search" name = "recskill1" placeholder = "Skill 1" value="<?php print isset($recskill1) ? $recskill1 : ''; ?>"></td>
          </tr>
          <tr>
            <td>Skill 2: </td>
            <td><input type = "search" name = "recskill2" placeholder = "Skill 2" value="<?php print isset($recskill2) ? $recskill2 : ''; ?>"></td>
          </tr>
          <tr>
            <td>Skill 3: </td>
            <td><input type = "search" name = "recskill3" placeholder = "Skill 3" value="<?php print isset($recskill3) ? $recskill3 : ''; ?>"></td>
          </tr>
          <tr>
            <td>Skill 4: </td>
            <td><input type = "search" name = "recskill4" placeholder = "Skill 4" value="<?php print isset($recskill4) ? $recskill4 : ''; ?>"></td>
          </tr>
          <tr>
            <td>Skill 5: </td>
            <td><input type = "search" name = "recskill5" placeholder = "Skill 5" value="<?php print isset($recskill5) ? $recskill5 : ''; ?>"></td>
          </tr>
          <tr class="blank_row"><td bgcolor="azure" colspan="3">&nbsp;</tr>

          <tr>
          <td>Starting Date:</td>
          <td><input type = "date" name = "recdate" value = "<?php print isset($recdate) ? $recdate : ''; ?>"></td>
          </tr>
        </table>
        <h3 class = "receducation">Education</h3>
        <table>
          <tr>
            <td>Minimum Education: </td>
            <td>
              <?php
                if (is_null($reclevel) or empty($reclevel)) {
              ?>
              <select id="lev" name="reclevel">
                <option value="NULL">Select</option>
                <option value="HS">High School</option>
                <option value="AS">Associates</option>
                <option value="BA">Bachelor's</option>
                <option value="MS">Master's</option>
                <option value="DR">Doctorate</option>
              </select>
              <?php } else {?>
              <select id="lev" name="reclevel">
                <option value="NULL"<?php if ($reclevel== "NULL"): ?> selected="selected"<?php endif; ?>>Select</option>
                <option value="HS"<?php if ($reclevel== "HS"): ?> selected="selected"<?php endif; ?>>High School</option>
                <option value="AS"<?php if ($reclevel== "AS"): ?> selected="selected"<?php endif; ?>>Associates</option>
                <option value="BA"<?php if ($reclevel== "BA"): ?> selected="selected"<?php endif; ?>>Bachelor's</option>
                <option value="MS"<?php if ($reclevel== "MS"): ?> selected="selected"<?php endif; ?>>Master's</option>
                <option value="DR"<?php if ($reclevel== "DR"): ?> selected="selected"<?php endif; ?>>Doctorate</option>
              </select>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td>Year:</td>
            <td>
              <?php
                if ($recyear == 0) {
              ?>
              <select id="yr" name="recyear">
                <option value="0">Select</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">Graduated</option>
              </select>
              <?php } else {?>
              <select id="yr" name="recyear">
                <option value="0"<?php if ($recyear== "NULL"): ?> selected="selected"<?php endif; ?>>Select</option>
                <option value="1"<?php if ($recyear== "1"): ?> selected="selected"<?php endif; ?>>1</option>
                <option value="2"<?php if ($recyear== "2"): ?> selected="selected"<?php endif; ?>>2</option>
                <option value="3"<?php if ($recyear== "3"): ?> selected="selected"<?php endif; ?>>3</option>
                <option value="4"<?php if ($recyear== "4"): ?> selected="selected"<?php endif; ?>>4</option>
                <option value="5"<?php if ($recyear== "5"): ?> selected="selected"<?php endif; ?>>5</option>
                <option value="6"<?php if ($recyear== "6"): ?> selected="selected"<?php endif; ?>>6</option>
                <option value="7"<?php if ($recyear== "0"): ?> selected="selected"<?php endif; ?>>Graduated</option>
              </select>
              <?php } ?>
            </td>
        </tr>
        <tr>
          <td>Top 3 Program / Major: </td>
          <td><input type = "search" name = "recprog1" placeholder = "1" value="<?php print isset($recprog1) ? $recprog1 : ''; ?>"></td>
          <td><input type = "search" name = "recprog2" placeholder = "2" value="<?php print isset($recprog2) ? $recprog2 : ''; ?>"></td>
          <td><input type = "search" name = "recprog3" placeholder = "3" value="<?php print isset($recprog3) ? $recprog3 : ''; ?>"></td>

        </tr>
        <tr>
          <td>Minimum GPA:</td>
          <td><input type = "search" name = "recgpa" placeholder = "GPA / 4.00" value="<?php print isset($recgpa) ? $recgpa : ''; ?>"></td>
        </tr>
      </table>
      <h3 class = "reclocation">Job Location</h3>
      <table class = "recremote" id = "recremote">
        <tr>
          <td>Remote?</td><td width = "16%"></td>
          <td>
            <?php
            if ($recremote == NULL) {
             ?>
            <select id="remote" name="recremote">
            <option value="NULL">Select</option>
            <option value="1">Yes</option>
            <option value="0">Temporarily</option>
            <option value="0">No</option>
            </select>
            <?php
          } else {
             ?>
           <select id="remote" name="recremote">
           <option value="NULL"<?php if ($recremote == "NULL"): ?> selected="selected"<?php endif; ?>>Select</option>
           <option value="1"<?php if ($recremote == "1"): ?> selected="selected"<?php endif; ?>>Yes</option>
           <option value="0"<?php if ($recremote == "0"): ?> selected="selected"<?php endif; ?>>Temporarily</option>
           <option value="0"<?php if ($recremote == "0"): ?> selected="selected"<?php endif; ?>>No</option>
           </select>
         <?php } ?>
          </td>
        </tr>
      </table>
      <div class = "reclocationinfo" id = "reclocationinfo">
        <table>
          <tr>
            <td>Address: </td>
            <td><input type ="search" name ="recaddress" placeholder = "Line 1" value="<?php print isset($recaddress) ? $recaddress : ''; ?>"></td>
          </tr>
          <tr>
            <td>City / Town: </td>
            <td><input type = "search" name = "reccity" placeholder = "City / Town" value="<?php print isset($reccity) ? $reccity : ''; ?>"></td>
          </tr>
          <tr>
            <td>State / Province: </td>
            <td><input type = "search" name = "recregion" placeholder = "State / Province" value="<?php print isset($recregion) ? $recregion : ''; ?>"></td>
          </tr>
          <tr>
            <td>Country: </td>
            <td><input type = "search" name = "reccountry" placeholder = "Country" value="<?php print isset($reccountry) ? $reccountry : ''; ?>"></td>
          </tr>
        </table>
      </div>

      <script>
        $(document).ready(function() {
          $('#remote').on('change', function() {
            if ( this.value == '0')
            {
              $("#reclocationinfo").show();
            }
            else
            {
              $("#reclocationinfo").hide();
            }
          });
        });
      </script>

      <div style="text-align:center">
          <input id = "search" type="submit" value="Search Users" name="search" onclick="submitted()">
      </div>
      </form>
    </div>
  </body>
</html>
