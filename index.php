<?php
  include('model/functions.php');
  include('views/header.php');
  if (isset($_GET['page'])) {
    if ($_GET['page'] == "timeline") {
      include("views/time-line.php");
    } elseif ($_GET['page'] == 'your-tweets') {
      include("views/your-tweets.php");
    }
  } else {
    include('views/main.php');
  }
  include('views/footer.php');
