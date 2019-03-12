<div class="container main-container">
  <div class="row">
    <div class="col-8">
      <h2>Jūsų žinutės</h2>
      <?php displayTweets('your-tweets'); ?>
    </div>
    <div class="col-4">
      <?php displaySearch(); ?>
      <hr>
      <?php displayTweetBox(); ?>
    </div>
  </div>
</div>
