<!DOCTYPE html>
<html>
  <head>
    <meta charset = "utf-8">
    <title> Login </title>
    <link type = "text/css" rel = "stylesheet" href = "css/login.css?version=1">
  </head>
  <body>
    <div>
      <h1> Login Page </h1>
      <div class = "errors">
        <?php
          session_start();
          include 'database.php';
          $user = $_POST["username"];
          $pass = $_POST["password"];
          if (isset($_POST["submit"])) {
            if (empty($user) || empty($pass)) {
              if (empty($user)) {
                echo "Please enter a valid value for User Login field." . "<br>";
              }
              if (empty($pass)) {
                echo "Please enter a valid value for Password field." . "<br>";
              }
            }
            else {
              $query = "SELECT * FROM tbl_accounts WHERE tbl_accounts.acc_login = '" . $user . "' AND tbl_accounts.acc_password = '" . sha1($pass) ."'";
              $req = $conn->query($query);
              $data = $req->num_rows;
              if ($data == 1) {
                $namequery = "SELECT tbl_accounts.acc_name FROM tbl_accounts WHERE tbl_accounts.acc_login = '" . $user . "' AND tbl_accounts.acc_password = '" . sha1($pass) ."'";
                $namereq = $conn->query($namequery);
                $row = $namereq->fetch_row();
                $_SESSION['logged_in'] = $row[0];
                header("Location: calendar.php");
                exit;
              }
              else {
                echo "Password is incorrect. Please check the password and try again.";
              }
            }
          }
        ?>
      </div>
      <div class = "content">
        <p> Please enter your user's login name and password. Both values are case sensitive. </p>
        <form name = "myform" action = "" method = "post">
          <label> Login: </label><input type = "text" name = "username"><br>
          <label> Password: </label><input type = "password" name = "password"><br><br>
          <input name = "submit" type = "Submit" value = "Submit">
        </form>
      </div>
      <div class = "footer">
        <p class = "end"> Tested with Chrome and Safari </p>
      </div>
    </div>
  </body>
</html>
