<!DOCTYPE html>
<html>
<head>
  <meta charset = "utf-8">
  <title> Calendar Input </title>
  <link type = "text/css" rel = "stylesheet" href = "css/form.css">
</head>
<body>
  <?php
    session_start();
    if (empty($_SESSION['logged_in'])) {
      header("Location: login.php");
      exit;
    }
    $user = $_POST["username"];
    $pass = $_POST["password"];
  ?>
  <div>
    <h1> Calendar Input </h1>
    <div class = "welcome">
      <p> Welcome <?php echo $_SESSION['logged_in'] . ","?> </p>
      <form name = "logout" action = "logout.php" method = "post">
        <input name = "submit" type = "Submit" value = "Logout">
      </form>
    </div>
    <nav>
      <a class = "link1" href = "calendar.php"> My Calendar </a>
      <a class = "link2" href = "form.php"> Form Input </a>
      <a class = "link2" href = "MVC/AdminController.php"> Admin </a>
    </nav>
    <div>
      <?php
        function cmp($a, $b) {
          return $a["starttime"]-$b["starttime"];
        }
        $filename = "calendar.txt";
        // Submit button
        if (isset($_POST["submit"])) {
          $event = $_POST["eventname"];
          $start = $_POST["starttime"];
          $end = $_POST["endtime"];
          $location = $_POST["location"];
          $day = $_POST["day"];
          if (empty($event) || empty($start) || empty($end) || empty($location) || empty($day)) {
            echo "<br>";
            if (empty($event)) {
              echo "Please provide a value for Event Name." . "<br>";
            }
            if (empty($start)) {
              echo "Please provide a value for Start Time." . "<br>";
            }
            if (empty($end)) {
              echo "Please provide a value for End Time." . "<br>";
            }
            if (empty($location)) {
              echo "Please provide a value for Location." . "<br>";
            }
            if (empty($day)) {
              echo "Please select a day of the week.";
            }
          }
          else {
            $json = json_decode(file_get_contents($filename), true);
            if ($json[$day] == null) {
              $json[$day] = array(array("eventname" => $event, "starttime" => $start, "endtime" => $end, "location" => $location));
            }
            else {
              array_push($json[$day], array("eventname" => $event, "starttime" => $start, "endtime" => $end, "location" => $location));
              // Sort the array in increasing order by starttime
              usort($json[$day], "cmp");
            }
            file_put_contents($filename, json_encode($json));
            header("Location: http://www-users.cselabs.umn.edu/~behtz001/calendar.php");
          }
        }
        // Clear button
        if (isset($_POST["clear"])) {
          if (file_exists($filename)) {
            unlink($filename);
            header("Location: http://www-users.cselabs.umn.edu/~behtz001/calendar.php");
          }
          else {
            header("Location: http://www-users.cselabs.umn.edu/~behtz001/calendar.php");
          }
        }
      ?>
    </div>
    <form class = "myform" name = "myform" action = "" method = "post">
      <label> Event Name </label><input type = "text" name = "eventname"><br>
      <label> Start Time </label><input type = "time" name = "starttime"><br>
      <label> End Time </label><input type = "time" name = "endtime"><br>
      <label> Location </label><input type = "text" name = "location"><br>
      <label> Day of the week </label><select name = "day">
                                        <option value = "" disabled selected> Please select a day </option>
                                        <option> Monday </option>
                                        <option> Tuesday </option>
                                        <option> Wednesday </option>
                                        <option> Thursday </option>
                                        <option> Friday </option>
                                      </select><br><br>
      <input name = "clear" type = "submit" value = "Clear">
      <input name = "submit" type = "submit" value = "Submit">
    </form>
    <p class = "end"> Tested with Chrome and Safari </p>
  </div>
</body>
</html>
