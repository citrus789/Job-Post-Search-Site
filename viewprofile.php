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
        $lastname = $row['LastName'];
        $email = $row['Email'];
        $phone = $row['Phone'];
        $country = $row['Country'];
        $region = $row['Region'];
        $city = $row['City'];
        $level = $row['Level'];
        $school = $row['School'];
        $program = $row['Program'];
        $year = $row['Year'];
        $gpa = $row['GPA'];

        $position1 = $row['Position1'];

        $image = $row['Image'];
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
        <div id = "editprofile"><a class = "active" href="editprofile.php">Edit Profile</a></div>
        <div id = "viewprofile" class = "active"><a href="viewprofile.php">View Profile</a></div>
      </div class = "bottomnavcontents">
    </div>
  </body>

</html>
