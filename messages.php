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
        $i = 0;
        //echo $postingcount;
        for ($i = 0; $i < $postingcount; $i++) {?>
          <div class = "postingcard">
            <div>
              <div class = "maintitle">
                <div class = "postingtitle"><?php echo unserialize($postinglist[$i])->position." at ".unserialize($postinglist[$i])->company;?></div>
              </div>
              <div>
                <div class = "moreinfo">
                  <div class = "salary"><?php echo "$".unserialize($postinglist[$i])->salarystart." - $".unserialize($postinglist[$i])->salaryend." (".unserialize($postinglist[$i])->currency.")"?></div>
                </div>
                <div class = "isremote">
                  <div class = "remote?"><?php echo "".unserialize($postinglist[$i])->remote.""?></div>
                </div>
              </div>
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
