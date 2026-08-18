<?php
$title = $title ?? "TraceX";
$metaDescription = $metaDescription ?? "Smart expense tracking and budgeting for a financially savvy life.";
$headLinks = $headLinks ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="icon" type="image/png" href="/public/assets/favicon.png">
<?php foreach ($headLinks as $headLink) { echo $headLink . "\n"; } ?>
</head>
