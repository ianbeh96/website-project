<?php
  $db_servername = 'cse-curly.cse.umn.edu';
  $db_port = 3306;
  $db_name = 'F17CS4131U6';
  $db_username = 'F17CS4131U6';
  $db_password = '2258';

  $conn = new mysqli($db_servername, $db_username, $db_password, $db_name, $db_port);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
?>
