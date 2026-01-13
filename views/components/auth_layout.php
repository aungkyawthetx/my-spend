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
  <link rel="preload" href="/public/assets/vendor/fonts/worksans/worksans.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="/src/output.css">
  <link rel="stylesheet" href="/src/input.css">
  <link rel="stylesheet" href="../../public/assets/vendor/fontawesome-free-7.1.0-web/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
  <div class="w-full max-w-md">
    <?= $content ?? '' ?>
  </div>
</body>
</html>
