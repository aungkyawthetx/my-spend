<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  $title = "Profile";

  ob_start();
?>

<div class="flex-1">
  <?php include __DIR__ . '/../views/profile/heading.php'; ?>
  <div class="max-w-6xl mx-auto space-y-6">
    <?php 
      include __DIR__ . '/../views/profile/profile-card.php';
      include __DIR__ . '/../views/profile/account-settings.php';
      include __DIR__ . '/../views/profile/statistics.php';
    ?>
  </div>
</div>

<?php
  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';
?>