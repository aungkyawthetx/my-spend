<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';
  $title = "Dashboard - TraceX";

  $currentMonthStart = date('Y-m-01');
  $nextMonthStart = date('Y-m-01', strtotime('+1 month'));
  $lastMonthStart = date('Y-m-01', strtotime('-1 month'));
  $sixMonthStart = date('Y-m-01', strtotime('-5 months'));

  // current/last month total expenses (single query)
  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total_expenses
    FROM expenses
    WHERE expense_date >= :current_start
      AND expense_date < :next_start
      AND user_id = :user_id
  ");
  $stmt->execute([
    ':current_start' => $currentMonthStart,
    ':next_start' => $nextMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $totalExpenses = (float) $stmt->fetchColumn();

  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total_expenses
    FROM expenses
    WHERE expense_date >= :last_start
      AND expense_date < :current_start
      AND user_id = :user_id
  ");
  $stmt->execute([
    ':last_start' => $lastMonthStart,
    ':current_start' => $currentMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $lastMonthTotal = (float) $stmt->fetchColumn();
  $isUp = $totalExpenses >= $lastMonthTotal;
  $percent = $lastMonthTotal > 0 ? (($totalExpenses - $lastMonthTotal) / $lastMonthTotal) * 100 : ($totalExpenses > 0 ? 100 : 0);

  // current/last month total budgets
  $stmt = $pdo->prepare("
    SELECT
      COALESCE(SUM(CASE WHEN month_year >= :current_start AND month_year < :next_start THEN amount ELSE 0 END), 0) AS current_total,
      COALESCE(SUM(CASE WHEN month_year >= :last_start AND month_year < :current_start THEN amount ELSE 0 END), 0) AS last_total
    FROM budgets
    WHERE user_id = :user_id
      AND month_year >= :last_start
      AND month_year < :next_start
  ");
  $stmt->execute([
    ':last_start' => $lastMonthStart,
    ':current_start' => $currentMonthStart,
    ':next_start' => $nextMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $budgetTotals = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['current_total' => 0, 'last_total' => 0];
  $monthlyBudgetTotal = (float) ($budgetTotals['current_total'] ?? 0);
  $lastMonthBudgetTotal = (float) ($budgetTotals['last_total'] ?? 0);
  $budgetIsUp = $monthlyBudgetTotal >= $lastMonthBudgetTotal;
  $budgetPercent = $lastMonthBudgetTotal > 0
    ? (($monthlyBudgetTotal - $lastMonthBudgetTotal) / $lastMonthBudgetTotal) * 100
    : ($monthlyBudgetTotal > 0 ? 100 : 0);

  // current/last month total savings deposits
  $stmt = $pdo->prepare("
    SELECT
      COALESCE(SUM(CASE WHEN created_at >= :current_start AND created_at < :next_start THEN amount ELSE 0 END), 0) AS current_total,
      COALESCE(SUM(CASE WHEN created_at >= :last_start AND created_at < :current_start THEN amount ELSE 0 END), 0) AS last_total
    FROM saving_transactions
    WHERE user_id = :user_id
      AND type = 'deposit'
      AND created_at >= :last_start
      AND created_at < :next_start
  ");
  $stmt->execute([
    ':last_start' => $lastMonthStart,
    ':current_start' => $currentMonthStart,
    ':next_start' => $nextMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $savingsTotals = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['current_total' => 0, 'last_total' => 0];
  $monthlySavingsDeposits = (float) ($savingsTotals['current_total'] ?? 0);
  $lastMonthSavingsDeposits = (float) ($savingsTotals['last_total'] ?? 0);
  $savingsIsUp = $monthlySavingsDeposits >= $lastMonthSavingsDeposits;
  $savingsPercent = $lastMonthSavingsDeposits > 0
    ? (($monthlySavingsDeposits - $lastMonthSavingsDeposits) / $lastMonthSavingsDeposits) * 100
    : ($monthlySavingsDeposits > 0 ? 100 : 0);

  // categories count (used by dashboard card)
  if (tableHasColumn($pdo, 'categories', 'user_id')) {
    $stmt = $pdo->prepare("
      SELECT COUNT(*)
      FROM categories
      WHERE user_id IS NULL OR user_id = :user_id
    ");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
  } else {
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
  }
  $categoriesCount = (int) $stmt->fetchColumn();

  // monthly expenses for last 6 months (including current month)
  $monthMap = [];
  for ($i = 5; $i >= 0; $i--) {
    $key = date('Y-m', strtotime("-{$i} months"));
    $monthMap[$key] = 0.0;
  }

  $stmt = $pdo->prepare("
    SELECT DATE_FORMAT(expense_date, '%Y-%m') AS ym, COALESCE(SUM(amount), 0) AS total
    FROM expenses
    WHERE user_id = :user_id
      AND expense_date >= :six_month_start
      AND expense_date < :next_start
    GROUP BY DATE_FORMAT(expense_date, '%Y-%m')
    ORDER BY ym ASC
  ");
  $stmt->execute([
    ':six_month_start' => $sixMonthStart,
    ':next_start' => $nextMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $monthlyRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($monthlyRows as $row) {
    if (isset($monthMap[$row['ym']])) {
      $monthMap[$row['ym']] = (float) $row['total'];
    }
  }
  $monthlyChartLabels = array_map(
    fn($ym) => date('M', strtotime($ym . '-01')),
    array_keys($monthMap)
  );
  $monthlyChartValues = array_values($monthMap);

  // current month category breakdown
  $stmt = $pdo->prepare("
    SELECT COALESCE(c.name, 'Uncategorized') AS category_name, COALESCE(SUM(e.amount), 0) AS total
    FROM expenses e
    LEFT JOIN categories c ON c.id = e.category_id
    WHERE e.user_id = :user_id
      AND e.expense_date >= :current_start
      AND e.expense_date < :next_start
    GROUP BY e.category_id, c.name
    ORDER BY total DESC
    LIMIT 6
  ");
  $stmt->execute([
    ':current_start' => $currentMonthStart,
    ':next_start' => $nextMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $breakdownRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $breakdownLabels = array_map(fn($row) => $row['category_name'], $breakdownRows);
  $breakdownValues = array_map(fn($row) => (float) $row['total'], $breakdownRows);

  // current month budget progress by category
  $stmt = $pdo->prepare("
    SELECT
      b.id,
      b.amount,
      b.month_year,
      COALESCE(c.name, 'Uncategorized') AS category_name,
      COALESCE(SUM(e.amount), 0) AS spent_amount
    FROM budgets b
    LEFT JOIN categories c ON c.id = b.category_id
    LEFT JOIN expenses e
      ON e.user_id = b.user_id
     AND e.category_id = b.category_id
     AND e.expense_date >= :current_start
     AND e.expense_date < :next_start
    WHERE b.user_id = :user_id
      AND b.month_year >= :current_start
      AND b.month_year < :next_start
    GROUP BY b.id, b.amount, b.month_year, c.name
    ORDER BY b.amount DESC, b.id DESC
    LIMIT 5
  ");
  $stmt->execute([
    ':current_start' => $currentMonthStart,
    ':next_start' => $nextMonthStart,
    ':user_id' => $_SESSION['user_id'],
  ]);
  $budgetProgressItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // recent transactions
  $stmt = $pdo->prepare("
    SELECT
      e.expense_date,
      e.note,
      e.amount,
      e.status,
      COALESCE(c.name, 'Uncategorized') AS category_name
    FROM expenses e
    LEFT JOIN categories c ON c.id = e.category_id
    WHERE e.user_id = :user_id
    ORDER BY e.expense_date DESC, e.id DESC
    LIMIT 5
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $recentTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ob_start();
  include __DIR__ . '/../views/home/welcome-text-and-cards.php';
  include __DIR__ . '/../views/home/charts.php';
  include __DIR__ . '/../views/home/transactions-and-progress.php';

  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';

?>
