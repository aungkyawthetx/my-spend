<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';
  $title = "Categories - MySpend";

  $sql = "SELECT * FROM categories";
  $params = [];
  $search = $_GET['search'] ?? '';
  if(!empty($search)) {
    $sql .= " WHERE name LIKE :search OR description LIKE :search";
    $params[':search'] = '%' . $search . '%';
  }

  $cat_stmt = $pdo->prepare($sql);
  $cat_stmt->execute($params);
  $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

  // expense counts per category
  $stmt = $pdo->prepare("SELECT category_id, COUNT(*) AS total FROM expenses WHERE user_id = :user_id GROUP BY category_id");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $expenseCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<?php
  ob_start();
  include __DIR__ . '/../views/categories/category-view.php';
  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';
?>