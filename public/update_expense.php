<?php
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';
  require __DIR__ . '/../src/helpers/flash.php';

  $hasCategoryUserId = tableHasColumn($pdo, 'categories', 'user_id');
  $hasPaymentMethodUserId = tableHasColumn($pdo, 'payment_methods', 'user_id');

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdateExpense'])) {
    $id = isset($_POST['edit_expense_id']) ? (int) $_POST['edit_expense_id'] : 0;
    $date = trim($_POST['expense_date'] ?? '');
    $amount = $_POST['amount'] ?? '';
    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $payment_method_id = isset($_POST['payment_method']) ? (int) $_POST['payment_method'] : 0;
    $note = trim($_POST['note'] ?? '');
    $status = isset($_POST['paid']) ? 'paid' : 'unpaid';
    $errors = [];

    if ($id <= 0) {
      $errors[] = 'Invalid expense ID.';
    }
    if (empty($date) || strtotime($date) === false) {
      $errors[] = 'Invalid expense date.';
    }
    if (!is_numeric($amount) || (float) $amount <= 0) {
      $errors[] = 'Amount must be greater than zero.';
    }
    if ($category_id <= 0) {
      $errors[] = 'Category is required.';
    }
    if ($payment_method_id <= 0) {
      $errors[] = 'Payment method is required.';
    }

    if (empty($errors)) {
      if ($hasCategoryUserId) {
        $catStmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id AND (user_id IS NULL OR user_id = :user_id)");
        $catStmt->execute([
          ':id' => $category_id,
          ':user_id' => $_SESSION['user_id']
        ]);
      } else {
        $catStmt = $pdo->prepare("SELECT id FROM categories WHERE id = :id");
        $catStmt->execute([':id' => $category_id]);
      }
      if (!$catStmt->fetchColumn()) {
        $errors[] = 'Selected category is invalid.';
      }

      if ($hasPaymentMethodUserId) {
        $payStmt = $pdo->prepare("SELECT id FROM payment_methods WHERE id = :id AND (user_id IS NULL OR user_id = :user_id)");
        $payStmt->execute([
          ':id' => $payment_method_id,
          ':user_id' => $_SESSION['user_id']
        ]);
      } else {
        $payStmt = $pdo->prepare("SELECT id FROM payment_methods WHERE id = :id");
        $payStmt->execute([':id' => $payment_method_id]);
      }
      if (!$payStmt->fetchColumn()) {
        $errors[] = 'Selected payment method is invalid.';
      }
    }

    if (!empty($errors)) {
      setFlash('error', $errors[0]);
      header("Location: expense.php");
      exit;
    }

    $stmt = $pdo->prepare("
      UPDATE expenses 
        SET expense_date = :expense_date,
          amount = :amount,
          category_id = :category_id,
          payment_method_id = :payment_method_id,
          note = :note,
          status = :status
        WHERE id = :id AND user_id = :user_id
    ");
    $stmt->execute([
      ':expense_date' => $date,
      ':amount' => (float) $amount,
      ':category_id' => $category_id,
      ':payment_method_id' => $payment_method_id,
      ':note' => $note,
      ':status' => $status,
      ':id' => $id,
      ':user_id' => $_SESSION['user_id']
    ]);

    if ($stmt->rowCount() === 0) {
      setFlash('error', 'Expense not found or access denied.');
      header("Location: expenses.php");
      exit;
    }

    setFlash('success', 'Expense has been updated!');
    header("Location: expenses.php");
    exit;
  }

?>
