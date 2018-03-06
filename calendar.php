<!DOCTYPE html>
<html>
  <head>
    <meta charset = "utf-8">
    <title> Calendar </title>
    <link type = "text/css" rel = "stylesheet" href = "css/calendar.css?version=1">
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
    <div class = "header">
      <h1> My Calendar </h1>
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
    </div>
    <div class = "table">
      <?php
        $filename = "calendar.txt";
        if (!file_exists($filename)) {
          echo "<p class = \"msg\">Calendar has no events. Please use the input page to enter some events.</p>";
        }
        else {
          $data = file_get_contents($filename);
          $json_obj = json_decode($data, true);
          echo "<table class = \"center\"><tbody>";
          // Get the days available
          $days = array_keys($json_obj);
          $counter = 1;
          $day_array = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
          // Access the days
          foreach ($day_array as $i) {
            if (in_array($i, $days)) {
              echo "\n\t\t<tr class = \"r" . strval($counter) ."\">\n\t\t\t<th scope = \"row\"><span>" . $i . "</span></th>\n";
              foreach ($json_obj[$i] as $j) {
                echo "\t\t\t<td>\n\t\t\t\t<p class = \"time\"> ". $j["starttime"] . " - " . $j["endtime"] . " </p>\n\t\t\t\t";
                echo "<p class = \"location\"> " . $j["eventname"] . " - " . $j["location"] . " </p>\n\t\t\t</td>\n";
              }
              echo "\t\t</tr>\n";
              $counter++;
            }
          }
          echo "</tbody></table>";
        }
      ?>
    </div>
    <div class = "myform">
      <form>
        <label> Radius: <input id = "radius" type = "text" value = ""> </label> <button onclick = "findrestaurant(); return false;"> Find Nearby Restaurant </button><br>
        <label> Destination: <input id = "destination" type = "text" requried = "required" value = ""> </label> <button onclick = "direction(); return false;"> Get Directions </button><br>
        <label> Walking <input name = "option" class = "transport" type = "radio" value = "WALKING"> </label>
        <label> Driving <input name = "option" class = "transport" type = "radio" value = "DRIVING"> </label>
        <label> Transit <input name = "option" class = "transport" type = "radio" value = "TRANSIT"> </label>
        <label> Bicycling <input name = "option" class = "transport" type = "radio" value = "BICYCLING"> </label>
      </form>
    </div>
    <div class = "container">
      <div id = "map"></div>
      <div id = "panel"></div>
    </div>
    <div class = "footer">
      <p class = "end"> Tested with Chrome and Safari </p>
    </div>
    <script type = "text/javascript" src = "js/calendar.js"></script>
    <script async defer src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBY04AjBxi54n9QtKKoUvjvnMG9QjfOrLk&libraries=places&callback=initMap"></script>
  </body>
</html>
