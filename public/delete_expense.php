<?php
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';
  require __DIR__.'/../src/helpers/flash.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDeleteExpense'])) {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id <= 0) {
      setFlash('error', 'Invalid expense ID.');
      header("Location: expense.php");
      exit;
    }

    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
      ":id" => $id,
      ":user_id" => $_SESSION['user_id']
    ]);

    if ($stmt->rowCount() === 0) {
      setFlash('error', 'Expense not found or access denied.');
      header("Location: expenses.php");
      exit;
    }

    setFlash('success', 'Expense has been deleted!');
    header("Location: expenses.php");
    exit;
  }
?>
