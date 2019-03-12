<?php
  session_start();
  $link = mysqli_connect("localhost", "root", "root", "tviteris", "3307");

  if (mysqli_connect_error()) {
    print_r(mysqli_connect_error());
    exit();
  }
  if (isset($_GET['function'])) {
    if ($_GET['function'] == "logout") {
      session_unset();
    }
  }

  function time_since($since) {
      $chunks = array(
          array(60 * 60 * 24 * 365 , 'year'),
          array(60 * 60 * 24 * 30 , 'month'),
          array(60 * 60 * 24 * 7, 'week'),
          array(60 * 60 * 24 , 'day'),
          array(60 * 60 , 'hour'),
          array(60 , 'minute'),
          array(1 , 'second')
      );
      for ($i = 0, $j = count($chunks); $i < $j; $i++) {
          $seconds = $chunks[$i][0];
          $name = $chunks[$i][1];
          if (($count = floor($since / $seconds)) != 0) {
              break;
          }
      }
      $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
      return $print;
  }

  function displayTweets($type)
  {
    global $link;
    if ($type == 'public') {
      $whereClause = "";
    } elseif ($type == 'isFollowing') {
      $manoSQL = "SELECT * FROM isfollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id']);
      $results = mysqli_query($link, $manoSQL);
      $whereClause = "";
      while ($row = mysqli_fetch_assoc($results)) {
        if ($whereClause == "") {
          $whereClause = "WHERE";
        } else {
         $whereClause.= " OR";
        }
        $whereClause.= " user_id = ".$row['isFollowing'];
      }
    }
    $manoSQL = "SELECT * FROM tweets ".$whereClause." ORDER BY `date_time` DESC LIMIT 10";
    $result = mysqli_query($link, $manoSQL);
    if (mysqli_num_rows($result) == 0) {
      echo "Nėra jokių žinučių.";
    } else {
      while ($row = mysqli_fetch_assoc($result)) {
        $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['user_id'])." LIMIT 1";
        $userQueryResult = mysqli_query($link, $userQuery);
        $user = mysqli_fetch_assoc($userQueryResult);
        echo "<div class='tweet'><p>{$user['email']} <span class='time'>".time_since(time() - strtotime($row['date_time']))." ago</span>:</p>";
        echo "<p>{$row['tweet']}</p>";
        echo "<p><a class='btn btn-primary toggleFollow' data-userId='{$row['user_id']}'>";
        $isFollowingQuery = "SELECT * FROM isfollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ".mysqli_real_escape_string($link, $row['user_id'])." LIMIT 1";
        $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
        if (mysqli_num_rows($isFollowingQueryResult) > 0) {
          echo "Nebesekti";
        } else {
          echo "Sekti";
        }
        echo "</a></p></div>";
      }
    }
  }
  function displaySearch()
  {
    echo '<div class="form-inline">
            <input type="text" class="form-control mb-2 mr-sm-2" id="search" placeholder="Paieška">
            <button class="btn btn-primary mb-2">Paieška</button>
          </div>';
  }

  function displayTweetBox()
  {
    if (isset($_SESSION['id'])) {
      if ($_SESSION['id'] > 0) {
        echo '<form">
                <textarea class="form-control mb-2 mr-sm-2" id="tweetContent"></textarea>
                <button type="submit" class="btn btn-primary mb-2">Skelbti žinutę!</button>
              </form>';
      }
    }
  }
