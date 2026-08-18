<?php
require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
require_once __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/helpers/flash.php';
require_once __DIR__ . '/../src/helpers/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['btnUpdateExpense'])) {
  exit;
}

verifyCsrf();

$userId = (int) $_SESSION['user_id'];
$expenseId = (int) ($_POST['edit_expense_id'] ?? 0);
$expenseDate = trim($_POST['expense_date'] ?? '');
$amount = $_POST['amount'] ?? '';
$categoryId = (int) ($_POST['category_id'] ?? 0);
$paymentMethodId = (int) ($_POST['payment_method'] ?? 0);
$note = trim($_POST['note'] ?? '');
$errors = [];

if ($expenseId <= 0) {
  $errors[] = 'Invalid expense ID.';
}
if ($expenseDate === '' || strtotime($expenseDate) === false) {
  $errors[] = 'Invalid expense date.';
}
if (!is_numeric($amount) || (float) $amount <= 0) {
  $errors[] = 'Amount must be greater than zero.';
}
if ($categoryId <= 0) {
  $errors[] = 'Category is required.';
}
if ($paymentMethodId <= 0) {
  $errors[] = 'Payment method is required.';
}

$isValidOption = function (string $table, int $id) use ($pdo, $userId): bool {
  $hasUserId = tableHasColumn($pdo, $table, 'user_id');
  $sql = "SELECT id FROM {$table} WHERE id = :id";
  $params = [':id' => $id];

  if ($hasUserId) {
    $sql .= ' AND (user_id IS NULL OR user_id = :user_id)';
    $params[':user_id'] = $userId;
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  return (bool) $stmt->fetchColumn();
};

if (!$errors && !$isValidOption('categories', $categoryId)) {
  $errors[] = 'Selected category is invalid.';
}
if (!$errors && !$isValidOption('payment_methods', $paymentMethodId)) {
  $errors[] = 'Selected payment method is invalid.';
}

if ($errors) {
  setFlash('error', $errors[0]);
  header('Location: expenses.php');
  exit;
}

$stmt = $pdo->prepare('UPDATE expenses SET expense_date = :expense_date, amount = :amount, category_id = :category_id, payment_method_id = :payment_method_id, note = :note, status = :status WHERE id = :id AND user_id = :user_id');
$stmt->execute([
  ':expense_date' => $expenseDate,
  ':amount' => (float) $amount,
  ':category_id' => $categoryId,
  ':payment_method_id' => $paymentMethodId,
  ':note' => $note,
  ':status' => isset($_POST['paid']) ? 'paid' : 'unpaid',
  ':id' => $expenseId,
  ':user_id' => $userId,
]);

setFlash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Expense has been updated!' : 'Expense not found or access denied.');
header('Location: expenses.php');
exit;
