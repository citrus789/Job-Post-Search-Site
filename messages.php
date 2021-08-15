<?php
session_start();
if(isset($_SESSION["Email"]) || $_SESSION['loggedin'] == true) {
  $username = $_SESSION['Email'];

  $conn = new mysqli("localhost","root", "", "website");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
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
        $numcard = 0;
        //echo $postingcount;
        for ($i = 0; $i < $postingcount; $i++) {

          if ($numcard == 0 or $numcard % 3 == 0) {
          ?><div id = "messagecardpage" class = "messagecardpage">
    <?php } ?>

          <div class = "postingcard">
            <div>
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
            </div>
          </div>
          <?php $numcard ++;
          if ($numcard % 3 == 0) {
            $end = true;
            ?> </div> <?php
          }
          else {
            $end = false;
          }
        }
        if ($end == false) {
          ?> </div> <?php
        }
        if ($numcard > 3) { ?>
          <table class = "searchnav" id = "searchnav" style = "height: 25px;">
            <tr></tr>
          </table>
      </div>
      <script>
        <?php
        //Warning: unreadable shit below!!!

        for ($i = 1; $i <= ceil($numcard / 3); $i++) { ?>
          console.log("loop");
          $("#searchnav").find('tr').each(function() {
            $(this).append("<td id = searchnav<?php echo $i; ?> name = <?php echo $i; ?> onclick = showpage(this)><?php echo $i; ?></td>");
          });
          <?php if ($i == 1) {?>
            document.getElementById("searchnav1").setAttribute("class", "active");
            var currentpage = document.getElementsByClassName("messagecardpage");
            currentpage[0].style.display = "block";
            <?php for ($j = 1; $j < ceil($numcard / 3); $j++) { ?>
              currentpage[<?php echo $j;?>].style.display = "none";
      <?php }
          }
        } ?>
        function showpage(elem) {
          var table = document.getElementById("searchnav");
          var currentpage = document.getElementsByClassName("messagecardpage");

          for (let row of searchnav.rows) {
            for(let cell of row.cells) {
              cell.removeAttribute("class");
            }
          }

          <?php for ($j = 0; $j < ceil($numcard / 3); $j++) { ?>
            currentpage[<?php echo $j; ?>].style.display = "none";
          <?php } ?>
          elem.setAttribute("class", "active");
          currentpage[Number(elem.getAttribute("name") - 1)].style.display = "block";
        }
      </script>
    <?php } ?>
    </div>
  </body>
</html>
<?php
$result->close();
$conn->close();
?>
