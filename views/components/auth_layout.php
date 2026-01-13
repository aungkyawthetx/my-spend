<?php
if (!isset($title)) {
    $title = "BudgetBoard";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="icon" type="image/png" href="../../public/assets/extc.png">
  <link rel="stylesheet" href="/src/output.css?v=<?= time() ?>">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
  <div class="w-full max-w-md">
    <?= $content ?? '' ?>
  </div>
</body>
</html>
