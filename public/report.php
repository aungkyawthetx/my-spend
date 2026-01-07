<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  $title = "Reports - BudgetBoard";

  ob_start();
  include __DIR__ . '/../views/reports/header.php';
  include __DIR__ . '/../views/reports/filter.php';
  include __DIR__ . '/../views/reports/summary-cards.php';

?>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <?php include __DIR__ . '/../views/reports/charts.php'; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <?php include __DIR__ . '/../views/reports/secondary-report.php'; ?>
    <?php include __DIR__ . '/../views/reports/recent-expenses.php'; ?>
  </div>

<?php
  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';
?>