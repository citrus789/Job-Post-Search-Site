<?php
session_start();
if(isset($_SESSION["Email"]) || $_SESSION['loggedin'] == true) {
  $username = $_SESSION['Email'];

  $conn = new mysqli("localhost","root", "", "website");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
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
      <script>
      function showposting(id) {
        var postingindex = id;
        var posting = document.getElementById("viewpostingscard"+id);
        $('.viewpostingscard').each(function(i, obj) {
          obj.style.display = 'none';
        });
        posting.style.display = 'block';
      }
      </script>

      <div class = "viewpostingdetails" id = "viewpostingdetails">
        <?php for ($i = 0; $i < $postingcount; $i++) { ?>
        <div class = "viewpostingscard" id = "viewpostingscard<?php echo $i; ?>"  style = "display: none;">
          <div class = "viewpostingtitle">
        <?php echo unserialize($postinglist[$i])->position." at ".unserialize($postinglist[$i])->company; ?>
          </div>
          <div class = "viewpostingsalary">
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
  <?php } ?>
      </div>
    </div>
  </body>
</html>
<?php
$result->close();
$conn->close();
?>
