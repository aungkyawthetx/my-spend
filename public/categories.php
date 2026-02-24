<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';
  $title = "Categories - TraceX";

  $hasCategoryUserId = tableHasColumn($pdo, 'categories', 'user_id');
  $sql = "SELECT * FROM categories";
  $where = [];
  $params = [];

  if ($hasCategoryUserId) {
    $where[] = "(user_id IS NULL OR user_id = :user_id)";
    $params[':user_id'] = $_SESSION['user_id'];
  }

  $search = $_GET['search'] ?? '';
  if(!empty($search)) {
    $where[] = "(name LIKE :search OR description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
  }

  if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
  }

  $sql .= " ORDER BY name ASC";

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
