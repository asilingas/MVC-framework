<?php
  include('model/functions.php');
  if ($_GET['action'] == "loginSignup") {
    $error = "";
    if (!$_POST['email']) {
      $error = "An email address is required!";
    } elseif (!$_POST['password']) {
      $error = "A password is required!";
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
      $error = "A valid email address is required!";
    }
    if ($error != "") {
      echo $error;
      exit();
    }
    if($_POST['loginActive'] == "0") {
      $manoSQL = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
      $results = mysqli_query($link, $manoSQL);
      if (mysqli_num_rows($results) > 0) {
        $error = "That email address is already taken!";
      } else {
        $manoSQL = "INSERT INTO users (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
        if (mysqli_query($link, $manoSQL)) {
          $_SESSION['id'] = mysqli_insert_id($link);
          $manoSQL = "UPDATE users SET password = '".hash('sha512', $_POST['password'])."' WHERE id = ".$_SESSION['id']." LIMIT 1";
          mysqli_query($link, $manoSQL);
          echo 1;
        } else {
          $error = "Could not create user!";
        }
      }
    } else {
      $manoSQL = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
      $results = mysqli_query($link, $manoSQL);
      $row = mysqli_fetch_assoc($results);
      if ($row['password'] == hash('sha512', $_POST['password'])) {
        echo 1;
        $_SESSION['id'] = $row['id'];
      } else {
        $error = "Could not find username & password combination!";
      }

    }
    if ($error != "") {
      echo $error;
      exit();
    }
  }
  if ($_GET['action'] == 'toggleFollow') {
    $manoSQL = "SELECT * FROM isfollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ".mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";
    $results = mysqli_query($link, $manoSQL);
    if (mysqli_num_rows($results) > 0) {
      $row = mysqli_fetch_assoc($results);
      mysqli_query($link, "DELETE FROM isfollowing WHERE id = ".mysqli_real_escape_string($link, $row['id'])." LIMIT 1");
      echo "1";
    } else {
      mysqli_query($link, "INSERT INTO isfollowing (follower, isFollowing) VALUES (".mysqli_real_escape_string($link, $_SESSION['id']).", ".mysqli_real_escape_string($link, $_POST['userId']).")");
      echo "2";
    }
  }
