<?php
  require __DIR__ . '/../src/helpers/url.php';
  require __DIR__ . '/../src/helpers/flash.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';

  $title = "Expenses - BudgetBoard";

  $stmt = $pdo->prepare("SELECT * FROM categories");
  $stmt->execute();
  $category_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // get payment methods
  $sql = $pdo->prepare("SHOW COLUMNS FROM expenses LIKE 'payment_method'");
  $sql->execute();
  $row = $sql->fetch(PDO::FETCH_ASSOC);
  $type = $row['Type'];
  preg_match("/^enum\('(.*)'\)$/", $type, $matches);
  $options = explode("','", $matches[1]);

  $errors = [];

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveExpense'])) {
    $expense_date = $_POST['expense_date'] ?? null;
    $amount = $_POST['amount'] ?? 0;
    $description = trim($_POST['description'] ?? '');
    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : null;
    $payment_method = trim($_POST['payment_method'] ?? '');
    $status = isset($_POST['paid']) ? 1 : 0;
    $note = trim($_POST['note']);

    if(empty($expense_date)) {
      $errors['expense_date'] = "Expense date is required";
    }

    if(empty($amount)) {
      $errors['amount'] = "Amount is required";
    }

    if(empty($description)) {
      $errors['description'] = "Description is required";
    }

    if(empty($category_id)) {
        $errors['category'] = "Category is required";
      } else {
      $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
      $stmt->execute([$category_id]);
      $category_result = $stmt->fetch();
      if(!$category_result) {
        $errors['category'] = "Selected category is invalid";
      } 
      else {
        $category_id = $category_result['id'];
      }
    }

    if(empty($payment_method)) {
      $errors['payment_method'] = "Payment method is required";
    }

    if(empty($errors)) {
      $stmt = $pdo->prepare("INSERT INTO expenses(user_id, category_id, amount, description, expense_date, payment_method, status, note) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([
        $_SESSION['user_id'],
        $category_id,
        $amount,
        $description,
        $expense_date,
        $payment_method,
        $status,
        $note
      ]);
      setFlash('success', 'Expense has been added!');
      header("Location: expenses.php");
      exit;
    } 
    else {
      setFlash('error', 'Something went wrong!');
      header("Location: expenses.php");
      exit;
    }
  }

  // fetch expenses with category color and icon
  $stmt = $pdo->prepare("SELECT expenses.*, categories.name AS category_name, categories.color AS category_color, categories.id AS category_id FROM expenses LEFT JOIN categories ON expenses.category_id = categories.id");
  $stmt->execute();
  $expenses = $stmt->fetchAll();

  ob_start();
  include __DIR__ . '/../views/expenses/header.php';
  include __DIR__ . '/../views/expenses/filters.php';
?>

<div class="bg-white rounded-lg shadow overflow-hidden">
  <?php
    include __DIR__ . '/../views/expenses/table.php';
    include __DIR__ . '/../views/components/pagination.php';
  ?>
</div>

<?php
  include __DIR__ . '/../views/components/modals/add-expense-modal.php';
  include __DIR__ . '/../views/components/modals/edit-expense-modal.php';
  include __DIR__ . '/../views/components/modals/delete-expense-modal.php';
  $content = ob_get_clean();
  include __DIR__ . '/../views/components/layout.php';
?>

<?php
 $flash = getFlash();
 if($flash):
?>
  <script>
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: "<?= $flash['type'] ?>",
      title: <?= json_encode($flash['message']) ?>,
      showConfirmButton: false,
      timer: 1500,
      width: "500px",
      timerProgressBar: true
    });
  </script>
<?php endif; ?>