<!DOCTYPE html>
<html>
  <head>
    <meta charset = "utf-8">
    <title> Admin </title>
    <link type = "text/css" rel = "stylesheet" href = "../css/admin.css?version=1">
  </head>
  <body>
    <?php
      session_start();
      if (empty($_SESSION['logged_in'])) {
        header("Location: ../login.php");
        exit;
      }
    ?>
    <div>
      <div class = "welcome">
        <form name = "logout" action = "../logout.php" method = "post">
          <input class = "logout" name = "submit" type = "Submit" value = "Logout">
        </form>
        <p> Welcome <?php echo $_SESSION['logged_in'] . ","?> </p>
        <p> This page is protected from the public, and you can see a list of all users defined in the database. </p>
      </div>
      <nav>
        <a class = "link1" href = "../calendar.php"> My Calendar </a>
        <a class = "link2" href = "../form.php"> Form Input </a>
        <a class = "link2" href = "AdminController.php"> Admin </a>
      </nav>
      <?php
        spl_autoload_register(function ($class) {
            include $class . '.php';
        });

        class AdminController {
            private $model;

            public function __construct(AdminModel $model) {
                $this->model = $model;
            }

            public function runAdminPage($_controller, $_model) {
                $view = new AdminView($_controller, $_model);
                if (count($_POST)) {
                    if (isset($_POST['add'])) {
                      $msg = $_model->addUser($_POST['new_name'],
                                              $_POST['new_login'],
                                              $_POST['new_password']);
                      $view->outputTable("");
                      $view->outputAddUser($msg);
                      return;
                    } else if (isset($_POST['edit'])) {
                      $view->outputTable("");
                      $view->outputAddUser("");
                      return;
                    } else if (isset($_POST['delete'])) {
                      $view->outputTable($_model->deleteUser($_POST['delete']));
                      $view->outputAddUser("");
                        return;
                    } else if (isset($_POST['update'])) {
                      $msg = $_model->updateUser($_POST['update'],
                                                 $_POST['acc_name'],
                                                 $_POST['acc_login'],
                                                 $_POST['acc_password']);
                      $view->outputTable($msg);
                      $view->outputAddUser("");
                      return;
                    } else if (isset($_POST['cancel'])) {
                      $view->outputTable("");
                      $view->outputAddUser("");
                      return;
                    }
                } else {
                  $view->outputTable("");
                  $view->outputAddUser("");
                  return;
                }
            }
        }

        $model = new AdminModel();
        $controller = new AdminController($model);
        $controller->runAdminPage($controller, $model);
      ?>
      <div class = "footer">
        <p class = "end"> Tested with Chrome and Safari </p>
      </div>
    </div>
  </body>
</html>
