<?php
  session_start();
  if (empty($_SESSION['logged_in'])) {
    header("Location: ../login.php");
    exit;
  }

  class AdminView {
      private $model;
      private $controller;

      public function __construct(AdminController $controller, AdminModel $model) {
          $this->controller = $controller;
          $this->model = $model;
      }

      public function outputTable($msg) {
          $html = '<div class = "usertable">
                    <h1> List of Users </h1>
                    #msg
                    <form action = "AdminController.php" method = "post">
                      <table id = "users">
                        <thead>
                          <tr>
                            <th> ID </th>
                            <th> Name </th>
                            <th> Login </th>
                            <th> New Password </th>
                            <th> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          #content
                        </tbody>
                      </table>
                    </form>
                  </div>';
          $content = $this->model->populateTable();
          $html = preg_replace("/#content/", $content, $html);
          $html = preg_replace("/#msg/", $msg, $html);
          echo $html;
      }

      public function outputAddUser($msg) {
          $html = '<div class = "gap"></div>
                   <div class = "adduser">
                    <h1> Add New User </h1>
                    #msg
                    <form class = "adduser" action = "AdminController.php" method = "post">
                      <label> Name: </label><input class = "new" type = "text" name = "new_name"><br>
                			<label> Login: </label><input class = "new" type = "text" name = "new_login"><br>
                			<label> Password: </label><input class = "new" type = "password" name = "new_password"><br><br>
              		    <input class = "new" type = "submit" value = "Add User" name = "add">
                    </form>
                   </div>';
          $html = preg_replace("/#msg/", $msg, $html);
          echo $html;
      }
  }
?>
