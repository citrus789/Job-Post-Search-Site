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
        <a href="sentpostings.php"><div id = "viewprofile">Postings Sent</div></a>
        <a href="sentapplications.php"><div id = "viewprofile" class = "active">Applications Sent</div></a>
      </div>
    </div>

    <div class = "sentapplicationcontainer">
      <?php
      $select = "SELECT * FROM sent_application WHERE Email = '$username'";
      $result = $conn->query($select);
      $numcolumns = $result->field_count;
      $count = 0;
      $row = $result -> fetch_array(MYSQLI_NUM);
      for ($i = 1; $i < $numcolumns; $i++) {
        if ($row[$i] != "NULL" or !empty($row[$i])) {
          if ($count == 0) {
            echo "<table class = 'sentapplicationstable'><tr class = 'sentapplicationrow'><td></td><td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>Resume</td><td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>Cover Letter</td><td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>Website Link</td><td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>Additional File</td></tr><tr>";
            $count++;
          }
          echo "<td>".unserialize($row[$i])->position." at ".unserialize($row[$i])->company."</td>";
          if (isset(unserialize($row[$i])->resume)) {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>1</td>";
          }
          else {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>0</td>";
          }
          if (isset(unserialize($row[$i])->cv)) {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>1</td>";
          }
          else {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>0</td>";
          }
          if (isset(unserialize($row[$i])->weblink)) {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>1</td>";
          }
          else {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>0</td>";
          }
          if (isset(unserialize($row[$i])->otherfile)) {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>1</td>";
          }
          else {
            echo "<td style = 'padding: 10; border-collapse: collapse; border: 2px solid darkblue;'>0</td>";
          }
        }
      }
      if ($count == 1) {
        echo "</table>";
      }
       ?>
    </div>

  </body>
</html>
<?php
$conn->close();
?>
