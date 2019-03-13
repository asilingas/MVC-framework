<div class="container main-container">
  <div class="row">
    <div class="col-8">
      <?php if (isset($_GET['user_id'])) {?>
      <?php  displayTweets($_GET['user_id']);  ?>
      <?php } else { ?>
      <h2>Aktyvūs žmonės</h2>
      <?php displayUsers(); ?>
      <?php } ?>
    </div>
    <div class="col-4">
      <?php displaySearch(); ?>
      <hr>
      <?php displayTweetBox(); ?>
    </div>
  </div>
</div>
