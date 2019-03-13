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
    } elseif ($type == 'your-tweets') {
      $whereClause = "WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id']);
    } elseif ($type == 'search') {
      echo "<p>'".mysqli_real_escape_string($link, $_GET['q'])."' paieškos rezultatai:</p>";
      $whereClause = "WHERE tweet LIKE '%".mysqli_real_escape_string($link, $_GET['q'])."%'";
    } elseif (is_numeric($type)) {
      $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
      $userQueryResult = mysqli_query($link, $userQuery);
      $user = mysqli_fetch_assoc($userQueryResult);
      echo "<h2>{$user['email']} žinutės</h2>";
      $whereClause = "WHERE user_id = ".mysqli_real_escape_string($link, $_GET['user_id']);
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
        echo "<div class='tweet'><a href=?page=public-profiles&user_id={$user['id']}><p>{$user['email']}</a> <span class='time'>".time_since(time() - strtotime($row['date_time']))." ago</span>:</p>";
        echo "<p>{$row['tweet']}</p>";
        if (isset($_SESSION['id'])) {
          echo "<p><a class='btn btn-primary toggleFollow' data-userId='{$row['user_id']}'>";
          $isFollowingQuery = "SELECT * FROM isfollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ".mysqli_real_escape_string($link, $row['user_id'])." LIMIT 1";
          $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
          if (mysqli_num_rows($isFollowingQueryResult) > 0) {
            echo "Nebesekti";
          } else {
            echo "Sekti";
          }
        }
        echo "</a></p></div>";
      }
    }
  }
  function displaySearch()
  {
    echo '<form class="form-inline">
            <input type="hidden" name="page" value="search">
            <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="search" placeholder="Paieška">
            <button type="submit" class="btn btn-primary mb-2">Paieška</button>
          </form>';
  }

  function displayTweetBox()
  {
    if (isset($_SESSION['id'])) {
      if ($_SESSION['id'] > 0) {
        echo '<div id="tweetSuccess" class="alert alert-success">Jūsų žinutė paskelbta!</div>
              <div id="tweetFail" class="alert alert-danger"></div>
              <div">
                <textarea class="form-control mb-2 mr-sm-2" id="tweetContent"></textarea>
                <button id="postTweetButton" class="btn btn-primary mb-2">Skelbti žinutę!</button>
              </div>';
      }
    }
  }

  function displayUsers()
  {
    global $link;
    $manoSQL = "SELECT * FROM users LIMIT 20";
    $result = mysqli_query($link, $manoSQL);
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p><a href='?page=public-profiles&user_id={$row['id']}'>{$row['email']}</a></p>";
    }
  }
