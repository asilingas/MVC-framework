<?php
  session_start();
  $link= mysqli_connect("localhost", "root", "root", "tviteris", "3307");
  if (mysqli_connect_error()) {
    print_r(mysqli_connect_error());
    exit();
  }
  if (isset($_GET['function'])) {
    if ($_GET['function'] == "logout") {
      session_unset();
    }
  }
