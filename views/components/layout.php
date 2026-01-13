<?php
if (!isset($title)) {
  $title = "MySpend";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="icon" type="image/png" href="../../public/assets/logo.png">
  <link rel="stylesheet" href="/src/output.css?v=<?= time() ?>">
  <!-- fontawesome -->
  <link rel="stylesheet" href="../../public/assets/vendor/fontawesome-free-7.1.0-web/css/all.min.css?v=<?= time() ?>">
  <!-- date picker -->
  <script src="../../public/assets/vendor/flatpickr/flatpickr.min.js"></script>
  <link rel="stylesheet" href="../../public/assets/vendor/flatpickr/flatpickr.min.css">
  <!-- chart.js -->
  <script src="../../public/assets/vendor/chartjs/chart.umd.js"></script>
  <!-- sweetalert2 -->
  <script src="../../public/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="flex h-screen overflow-hidden">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="flex flex-col flex-1 overflow-hidden">
      <?php
        if (basename($_SERVER['SCRIPT_NAME']) !== 'profile.php') {
          include __DIR__ . '/navbar.php';
        }
      ?>
      <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        <?= $content ?? '' ?>
      </main>
    </div>
  </div>

  <script>
    flatpickr("#date-range", {
      mode: "range",
      dateFormat: "Y-m-d",
    });

    flatpickr("#expenseDate", {
      dateFormat: "Y-m-d",
      defaultDate: "today"
    });
  </script>
  <!-- Common JS -->
  <script src="/public/assets/js/index.js"></script>
  <script src="/public/assets/js/reports.js"></script>
  <script src="/public/assets/js/expenses.js"></script>
  <script src="/public/assets/js/categories.js"></script>
</body>
</html>
