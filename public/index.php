<?php
  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';
  $title = "Home - TraceX";

  // last month total expenses
  $sql = "
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM expenses
    WHERE expense_date >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01')
      AND expense_date <  DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')
      AND user_id = :user_id
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $lastMonthTotal = (float) $stmt->fetchColumn();

  // current month total expenses
  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total_expenses
    FROM expenses
    WHERE MONTH(expense_date) = MONTH(CURRENT_DATE())
    AND YEAR(expense_date) = YEAR(CURRENT_DATE())
    AND user_id = :user_id
  ");
  $stmt->execute(['user_id' => $_SESSION['user_id']]);
  $totalExpenses = (float) $stmt->fetchColumn();
  $isUp = $totalExpenses >= $lastMonthTotal;
  $percent = $lastMonthTotal > 0 ? (($totalExpenses - $lastMonthTotal) / $lastMonthTotal) * 100 : ($totalExpenses > 0 ? 100 : 0);

  // last month total budgets
  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM budgets
    WHERE user_id = :user_id
      AND month_year >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01')
      AND month_year < DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $lastMonthBudgetTotal = (float) $stmt->fetchColumn();

  // current month total budgets
  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM budgets
    WHERE user_id = :user_id
      AND month_year >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')
      AND month_year < DATE_FORMAT(CURRENT_DATE + INTERVAL 1 MONTH, '%Y-%m-01')
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $monthlyBudgetTotal = (float) $stmt->fetchColumn();
  $budgetIsUp = $monthlyBudgetTotal >= $lastMonthBudgetTotal;
  $budgetPercent = $lastMonthBudgetTotal > 0
    ? (($monthlyBudgetTotal - $lastMonthBudgetTotal) / $lastMonthBudgetTotal) * 100
    : ($monthlyBudgetTotal > 0 ? 100 : 0);

  // last month total savings deposits
  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM saving_transactions
    WHERE user_id = :user_id
      AND type = 'deposit'
      AND created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m-01')
      AND created_at < DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $lastMonthSavingsDeposits = (float) $stmt->fetchColumn();

  // current month total savings deposits
  $stmt = $pdo->prepare("
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM saving_transactions
    WHERE user_id = :user_id
      AND type = 'deposit'
      AND created_at >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')
      AND created_at < DATE_FORMAT(CURRENT_DATE + INTERVAL 1 MONTH, '%Y-%m-01')
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
  $monthlySavingsDeposits = (float) $stmt->fetchColumn();
  $savingsIsUp = $monthlySavingsDeposits >= $lastMonthSavingsDeposits;
  $savingsPercent = $lastMonthSavingsDeposits > 0
    ? (($monthlySavingsDeposits - $lastMonthSavingsDeposits) / $lastMonthSavingsDeposits) * 100
    : ($monthlySavingsDeposits > 0 ? 100 : 0);
  
  if (tableHasColumn($pdo, 'categories', 'user_id')) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE user_id IS NULL OR user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
  } else {
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
  }
  $categoriesCount = $stmt->fetchColumn();

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
      AND expense_date >= DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL 5 MONTH), '%Y-%m-01')
    GROUP BY DATE_FORMAT(expense_date, '%Y-%m')
    ORDER BY ym ASC
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
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
      AND MONTH(e.expense_date) = MONTH(CURRENT_DATE())
      AND YEAR(e.expense_date) = YEAR(CURRENT_DATE())
    GROUP BY e.category_id, c.name
    ORDER BY total DESC
    LIMIT 6
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
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
     AND YEAR(e.expense_date) = YEAR(b.month_year)
     AND MONTH(e.expense_date) = MONTH(b.month_year)
    WHERE b.user_id = :user_id
      AND b.month_year >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')
      AND b.month_year < DATE_FORMAT(CURRENT_DATE + INTERVAL 1 MONTH, '%Y-%m-01')
    GROUP BY b.id, b.amount, b.month_year, c.name
    ORDER BY b.amount DESC, b.id DESC
    LIMIT 5
  ");
  $stmt->execute([':user_id' => $_SESSION['user_id']]);
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
