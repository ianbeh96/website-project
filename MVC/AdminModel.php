<?php
  session_start();
  if (empty($_SESSION['logged_in'])) {
    header("Location: ../login.php");
    exit;
  }

  class AdminModel {
    private $db;
    private $tableElements;

    public function __construct() {
      include '../database.php';
      $this->db = $conn;
      $this->tableElements = '<tr>
                                  <td>
                                      #acc_id
                                  </td>
                                  <td>
                                      #acc_name
                                  </td>
                                  <td>
                                      #acc_login
                                  </td>
                                  <td>
                                      #acc_password
                                  </td>
                                  <td>
                                      <button type = "submit" value = "#acc_id" name = "edit"> Edit </button>
                                      <button type = "submit" value = "#acc_id" name = "delete"> Delete </button>
                                  </td>
                              </tr>';
    }

    public function populateTable() {
        $result = $this->db->query("SELECT * FROM tbl_accounts");
        if (empty($result)) {
            die("Error getting table contents.");
        }
        $content = "";
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            if (isset($_POST['edit']) && ($_POST['edit'] == $row['acc_id'])) {
                $content .= $this->editMode($row);
                unset($_POST['edit']);
                continue;
            } else {
                $content .= $this->formHTML($row);
            }
        }
        return $content;
    }

    public function editMode($row) {
        $html = $this->tableElements;
        # id
        $html = preg_replace('/#acc_id/', $row['acc_id'], $html);
        # name
        $editField = '<input type = "text" name = "acc_name" value = "' . $row['acc_name'] . '">';
        $html = preg_replace('/#acc_name/', $editField, $html);
        # login
        $editField = '<input type = "text" name = "acc_login" value = "' . $row['acc_login'] . '">';
        $html = preg_replace('/#acc_login/', $editField, $html);
        # password
        $editField = '<input type = "password" name = "acc_password" value = "">';
        $html = preg_replace('/#acc_password/', $editField, $html);
        # update
        $html = preg_replace('/Edit/', 'Update', $html);
        $html = preg_replace('/name = "edit"/', 'name = "update"', $html);
        # cancel
        $html = preg_replace('/Delete/', 'Cancel', $html);
        $html = preg_replace('/name = "delete"/', 'name = "cancel"', $html);
        return $html;
    }

    public function formHTML($row) {
        $values = array_keys($row);
        $html = $this->tableElements;
        foreach($values as $key) {
            if ($key != "acc_password") {
              $temp = "/#" . $key . "/";
              $html = preg_replace($temp, $row[$key], $html);
            }
            else {
              $temp = "/#" . $key . "/";
              $html = preg_replace($temp, "", $html);
            }
        }
        return $html;
    }

    public function addUser($name, $login, $password) {
        $msg = '';
        if (empty($name) || empty($login) || empty($password)) {
            if (empty($name)) {
              $msg .= "<p> Please enter a valid value for Name field. </p>";
            }
            if (empty($login)) {
              $msg .= "<p> Please enter a valid value for Login field. </p>";
            }
            if (empty($password)) {
              $msg .= "<p> Please enter a valid value for Password field. </p>";
            }
        }
        else {
            $check = $this->db->query("SELECT tbl_accounts.acc_login FROM tbl_accounts WHERE tbl_accounts.acc_login ='" . $login . "'");
            $numrows = $check->num_rows;
            if ($numrows == 0) {
                $insert = $this->db->query("INSERT INTO tbl_accounts (acc_name, acc_login, acc_password) VALUES ('" . $name . "', '" . $login . "', '" . sha1($password) . "')");
                if (empty($insert)) {
                    $msg .= "<p> Failed to insert user into database. </p>";
                }
                else {
                    $msg .= "<p> Successfully inserted user into database. </p>";
                }
            }
            else {
                $msg .= "<p> The login is used by another user. </p>";
            }
        }
        return $msg;
    }

    public function deleteUser($del) {
        $result = '';
        $delete = $this->db->query("DELETE FROM tbl_accounts WHERE tbl_accounts.acc_id ='" . $del . "'");
        if (empty($delete)) {
            $result .= "<p> Failed to delete user from the database. </p>";
        }
        else {
            $result .= "<p> Successfully deleted user from the database. </p>";
        }
        return $result;
    }

    public function updateUser($upd, $name, $login, $password) {
        $result = '';
        if (empty($name) || empty($login) || empty($password)) {
            if (empty($name)) {
              $result .= "<p> Please enter a valid value for Name field. </p>";
            }
            if (empty($login)) {
              $result .= "<p> Please enter a valid value for Login field. </p>";
            }
            if (empty($password)) {
              $result .= "<p> Please enter a valid value for Password field. </p>";
            }
        }
        else {
            $check = $this->db->query("SELECT * FROM tbl_accounts WHERE tbl_accounts.acc_login ='" . $login . "'");
            $numrows = $check->num_rows;
            if ($numrows == 0) {
                $update = $this->db->query("UPDATE tbl_accounts SET acc_name ='" . $name . "', acc_login ='" . $login . "', acc_password ='" . sha1($password) . "' WHERE acc_id ='" . $upd . "'");
                if (empty($update)) {
                    $result .= "<p> Failed to update user in the database. </p>";
                }
                else {
                    $result .= "<p> Successfully updated user in the database. </p>";
                }
            }
            else {
                $result .= "<p> The login is used by another user. </p>";
            }
        }
        return $result;
    }
  }
?>
