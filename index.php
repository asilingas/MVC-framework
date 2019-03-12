<?php
  include('model/functions.php');
  include('views/header.php');
  if (isset($_GET['page'])) {
    if ($_GET['page'] == "timeline") {
      include("views/time-line.php");
    }
  } else {
    include('views/main.php');
  }
  include('views/footer.php');
