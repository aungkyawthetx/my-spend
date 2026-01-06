<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  $title = "Home - BudgetBoard";

  ob_start();

  include __DIR__ . '/../views/home/welcometext.php';
  include __DIR__ . '/../views/home/stats-cards.php';
  include __DIR__ . '/../views/home/charts.php';

?>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <?php include __DIR__ . '/../views/home/recent-transactions.php'; ?>
    <?php include __DIR__ . '/../views/home/budget-progress.php'; ?>
  </div>

<?php
  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';
?>