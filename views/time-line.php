<div class="container main-container">
  <div class="row">
    <div class="col-8">
      <h2>Žinutės jums</h2>
      <?php displayTweets('isFollowing'); ?>
    </div>
    <div class="col-4">
      <?php displaySearch(); ?>
      <hr>
      <?php displayTweetBox(); ?>
    </div>
  </div>
</div>
