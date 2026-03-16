<?php
  require __DIR__ . '/../src/helpers/url.php';
  require __DIR__ . '/../src/helpers/flash.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';

  $title = "Insights - TraceX";

  $currentMonth = date('Y-m-01');
  $nextMonth = date('Y-m-01', strtotime('+1 month'));

  // Get spending per category this month
  $spendingStmt = $pdo->prepare("
    SELECT c.name, c.color, SUM(e.amount) as total_spent, COUNT(e.id) as expense_count
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = :user_id AND e.expense_date >= :start_date AND e.expense_date < :end_date
    GROUP BY e.category_id, c.name, c.color
    ORDER BY total_spent DESC
  ");
  $spendingStmt->execute([
    ':user_id' => $_SESSION['user_id'],
    ':start_date' => $currentMonth,
    ':end_date' => $nextMonth
  ]);
  $categorySpending = $spendingStmt->fetchAll(PDO::FETCH_ASSOC);

  // Get budgets for this month
  $budgetStmt = $pdo->prepare("
    SELECT c.name, b.amount as budget_amount
    FROM budgets b
    JOIN categories c ON b.category_id = c.id
    WHERE b.user_id = :user_id AND b.month_year = :month_year
  ");
  $budgetStmt->execute([
    ':user_id' => $_SESSION['user_id'],
    ':month_year' => $currentMonth
  ]);
  $budgets = $budgetStmt->fetchAll(PDO::FETCH_ASSOC);

  // Create a map of category to budget
  $budgetMap = [];
  foreach ($budgets as $budget) {
    $budgetMap[$budget['name']] = $budget['budget_amount'];
  }

  // Calculate total spending
  $totalSpent = 0;
  foreach ($categorySpending as $spend) {
    $totalSpent += $spend['total_spent'];
  }

  // Generate insights
  $insights = [];
  foreach ($categorySpending as $spend) {
    $category = $spend['name'];
    $spent = $spend['total_spent'];
    $percentage = $totalSpent > 0 ? ($spent / $totalSpent) * 100 : 0;

    $insight = [
      'category' => $category,
      'spent' => $spent,
      'percentage' => round($percentage, 1),
      'budget' => $budgetMap[$category] ?? null,
      'over_budget' => isset($budgetMap[$category]) && $spent > $budgetMap[$category],
      'suggestions' => []
    ];

    // Simple suggestions based on category and spending
    if ($category === 'Food & Dining' && $percentage > 30) {
      $insight['suggestions'][] = "Consider cooking at home more often to reduce dining out expenses.";
    }
    if ($category === 'Entertainment' && $percentage > 20) {
      $insight['suggestions'][] = "Look for free or low-cost entertainment alternatives.";
    }
    if ($category === 'Transportation' && $percentage > 25) {
      $insight['suggestions'][] = "Try carpooling or using public transport to save on fuel.";
    }
    if ($insight['over_budget']) {
      $insight['suggestions'][] = "You're over budget for this category. Consider reducing spending here.";
    }
    if ($percentage > 40) {
      $insight['suggestions'][] = "This category takes up a large portion of your spending. Review if all expenses are necessary.";
    }

    $insights[] = $insight;
  }

  ob_start();
  include __DIR__ . '/../views/insights/insights-view.php';

  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';
?>