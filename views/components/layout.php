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
  <link rel="stylesheet" href="../../public/fontawesome-free-7.1.0-web/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <!-- data picker package -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  <!-- Common JS -->
  <script src="/public/assets/js/index.js"></script>
  <script src="/public/assets/js/reports.js"></script>
  <script src="/public/assets/js/expenses.js"></script>
  <script src="/public/assets/js/categories.js"></script>
</body>
</html>
