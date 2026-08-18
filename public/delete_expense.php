<?php
require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
require_once __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/helpers/flash.php';
require_once __DIR__ . '/../src/helpers/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['btnDeleteExpense'])) {
  exit;
}

verifyCsrf();

$expenseId = (int) ($_POST['id'] ?? 0);
if ($expenseId <= 0) {
  setFlash('error', 'Invalid expense ID.');
  header('Location: expenses.php');
  exit;
}

$stmt = $pdo->prepare('DELETE FROM expenses WHERE id = :id AND user_id = :user_id');
$stmt->execute([
  ':id' => $expenseId,
  ':user_id' => $_SESSION['user_id'],
]);

setFlash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Expense has been deleted!' : 'Expense not found or access denied.');
header('Location: expenses.php');
exit;
