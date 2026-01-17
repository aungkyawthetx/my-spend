<?php
  require __DIR__ . '/../src/helpers/url.php';
  require __DIR__ . '/../src/helpers/flash.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  require_once __DIR__ . '/../src/bootstrap.php';

  $title = "Expenses - MySpend";

  $category_stmt = $pdo->prepare("SELECT * FROM categories");
  $category_stmt->execute();
  $category_items = $category_stmt->fetchAll(PDO::FETCH_ASSOC);
  // get payment methods
  $payment_stmt = $pdo->prepare("SELECT id, name, user_id FROM payment_methods WHERE user_id IS NULL OR user_id = ?");
  $payment_stmt->execute([$_SESSION['user_id']]);
  $payment_methods = $payment_stmt->fetchAll(PDO::FETCH_ASSOC);

  $errors = [];

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveExpense'])) {
    $expense_date = $_POST['expense_date'] ?? null;
    $amount = $_POST['amount'] ?? 0;
    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : null;
    $payment_method = isset($_POST['payment_method']) ? (int) $_POST['payment_method'] : null;
    $status = isset($_POST['paid']) ? 1 : 0;
    $note = trim($_POST['note']);

    if(empty($expense_date)) {
      $errors['expense_date'] = "Expense date is required";
    }

    if(empty($amount)) {
      $errors['amount'] = "Amount is required";
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
      $stmt = $pdo->prepare("INSERT INTO expenses(user_id, category_id, payment_method_id, amount, note, expense_date, status) VALUES(?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([
        $_SESSION['user_id'],
        $category_id,
        $payment_method,
        $amount,
        $note,
        $expense_date,
        $status
      ]);
      setFlash('success', 'Expense has been added!');
      header("Location: expense.php");
      exit;
    } 
    else {
      setFlash('error', 'Something went wrong!');
      header("Location: expense.php");
      exit;
    }
  }

  // fetch expenses
  $sql = "SELECT 
    expenses.*, 
    categories.name AS category_name, 
    categories.color AS category_color, 
    categories.id AS category_id,
    payment_methods.name AS payment_method,
    payment_methods.id AS payment_method_id
    FROM expenses 
    LEFT JOIN categories ON expenses.category_id = categories.id 
    LEFT JOIN payment_methods ON expenses.payment_method_id = payment_methods.id 
    WHERE expenses.user_id = :user_id";

  $params = [];
  $date_range = $_GET['date_range'] ?? '';
  $category_id = $_GET['category_id'] ?? '';
  $min_amount = $_GET['min_amount'] ?? '';
  $max_amount = $_GET['max_amount'] ?? '';

  if(!empty($date_range)) {
    if(str_contains($date_range, ' to ')) {
      [$startDate, $endDate] = explode(' to ', $date_range);
      $sql .= " AND expenses.expense_date BETWEEN :start_date AND :end_date";
      $params['start_date'] = $startDate;
      $params['end_date']   = $endDate;
    } else {
      $sql .= " AND expenses.expense_date = :expense_date";
      $params['expense_date'] = $date_range;
    }
  }

  if (!empty($category_id)) {
    $sql .= " AND expenses.category_id = :category_id";
    $params['category_id'] = $category_id;
  }

  if ($min_amount !== null && $min_amount !== '') {
    $sql .= " AND expenses.amount >= :min_amount";
    $params['min_amount'] = $min_amount;
  }

  if ($max_amount !== null && $max_amount !== '') {
    $sql .= " AND expenses.amount <= :max_amount";
    $params['max_amount'] = $max_amount;
  }

  $sql .= " ORDER BY expenses.expense_date DESC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['user_id' => $_SESSION['user_id']] + $params);
  $expenses = $stmt->fetchAll();

?>

<?php
  ob_start();
  include __DIR__ . '/../views/expenses/header-and-filter.php';
  include __DIR__ . '/../views/expenses/expense-view.php';
?>

<!-- add new modal -->
<div id="expenseModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur"></div>
      </div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                  <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                      <i class="fas fa-receipt text-blue-600"></i>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                      <h3 class="text-lg leading-6 font-medium text-gray-900" id="expense-modal-title">Add New Expense</h3>
                      <div class="mt-2">
                          <form id="expenseForm" method="POST" action="">
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                  <div>
                                      <label for="expense_date" class="block text-sm font-medium text-gray-700">Date</label>
                                      <input type="date" id="expense_date" name="expense_date" class="flatpickr mt-1 h-10 p-2 w-full sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['expense_date']) ? 'border-red-500' : '' ?>">
                                      <?php if (isset($errors['expense_date'])): ?>
                                          <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['expense_date']) ?></p>
                                      <?php endif; ?>
                                  </div>
                                  <div>
                                      <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                      <div class="mt-1 relative rounded-md">
                                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                              <span class="text-gray-500 sm:text-sm">$</span>
                                          </div>
                                          <input type="number" id="amount" name="amount" class="h-10 block w-full pl-7 pr-12 sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['amount']) ? 'border-red-500' : '' ?>" placeholder="0.00">
                                          <?php if (isset($errors['amount'])): ?>
                                              <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['amount']) ?></p>
                                          <?php endif; ?>
                                      </div>
                                  </div>
                              </div>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                  <div>
                                      <label for="categoty" class="block text-sm font-medium text-gray-700">Category</label>
                                      <select id="categoty" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm rounded-md cursor-pointer <?= isset($errors['category']) ? 'border-red-500' : '' ?>">
                                          <option value="">Category</option>
                                          <?php foreach($category_items as $item): ?>
                                              <option value="<?= $item['id']?>"> <?= $item['name'] ?> </option>
                                          <?php endforeach ?>
                                      </select>
                                      <?php if (isset($errors['category'])): ?>
                                          <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['category']) ?></p>
                                      <?php endif; ?>
                                  </div>
                                  <div>
                                      <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                      <select id="payment_method" name="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm rounded-md cursor-pointer <?= isset($errors['payment_method']) ? 'border-red-500' : '' ?>">
                                          <option value=""> Payment Method</option>
                                          <?php foreach ($payment_methods as $method): ?>
                                              <option value="<?= $method['id'] ?>"><?= $method['name'] ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                      <?php if (isset($errors['payment_method'])): ?>
                                          <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['payment_method']) ?></p>
                                      <?php endif; ?>
                                  </div>
                              </div>
                              <div class="mb-4">
                                  <label for="note" class="block text-sm font-medium text-gray-700">Notes</label>
                                  <textarea id="note" rows="3" name="note" class="mt-1 p-2 w-full sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your notes..."></textarea>
                              </div>
                              <div>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" id="status" name="paid" checked class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                      <span class="ml-2 text-sm text-gray-700">Mark as paid</span>
                                  </label>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button type="submit" name="btnSaveExpense" form="expenseForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                  Save Expense
              </button>
              <button onclick="closeAddExpenseModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                  Cancel
              </button>
          </div>
      </div>
  </div>
</div>
<!-- edit modal -->
<div id="editExpenseModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur"></div>
      </div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-receipt text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="expense-modal-title">Edit expense</h3>
                        <div class="mt-2">
                            <form id="editExpenseForm" method="POST" action="update_expense.php">
                                <input type="hidden" name="edit_expense_id" id="edit_expense_id">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="edit_expense_date" class="block text-sm font-medium text-gray-700">Date</label>
                                        <input type="date" id="edit_expense_date" name="expense_date" class="flatpickr mt-1 h-10 p-2 w-full sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['expense_date']) ? 'border-red-500' : '' ?>">
                                        <?php if (isset($errors['expense_date'])): ?>
                                            <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['expense_date']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="edit_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                        <div class="mt-1 relative rounded-md">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" id="edit_amount" name="amount" class="h-10 block w-full pl-7 pr-12 sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['amount']) ? 'border-red-500' : '' ?>" placeholder="0.00">
                                            <?php if (isset($errors['amount'])): ?>
                                                <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['amount']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="edit_category" class="block text-sm font-medium text-gray-700">Category</label>
                                        <select id="edit_category" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm rounded-md cursor-pointer <?= isset($errors['category']) ? 'border-red-500' : '' ?>">
                                            <option value="">Category</option>
                                            <?php foreach($category_items as $item): ?>
                                                <option value="<?= $item['id']?>"> <?= $item['name'] ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php if (isset($errors['category'])): ?>
                                            <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['category']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="edit_payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                        <select id="edit_payment_method" name="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm rounded-md cursor-pointer <?= isset($errors['payment_method']) ? 'border-red-500' : '' ?>">
                                            <option value=""> Payment method</option>
                                            <?php foreach ($payment_methods as $method): ?>
                                                <option value="<?= $method['id'] ?>"><?= $method['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['payment_method'])): ?>
                                            <p class="text-red-500 text-xs italic mt-1"><?= htmlspecialchars($errors['payment_method']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="edit_note" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea id="edit_note" rows="3" name="note" class="mt-1 p-2 w-full sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your notes..."></textarea>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="edit_status" name="paid" checked class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                        <span class="ml-2 text-sm text-gray-700">Mark as paid</span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    type="submit" 
                    name="btnUpdateExpense" 
                    form="editExpenseForm" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Update Expense
                </button>
                <button 
                    onclick="closeEditExpenseModal()" 
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<!-- delete modal -->
<div id="deleteExpenseModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                  <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                      <i class="fas fa-exclamation text-red-600"></i>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                      <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Expense</h3>
                      <div class="mt-2">
                          <p class="text-sm text-gray-500">Are you sure you want to delete this expense record? This action cannot be undone.</p>
                      </div>
                  </div>
              </div>
          </div>
          <form action="delete_expense.php" method="POST">
              <input type="hidden" name="id" id="delete-id">
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                  <button type="submit" name="btnDeleteExpense" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                      Delete
                  </button>
                  <button onclick="closeDeleteExpenseModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                      Cancel
                  </button>
              </div>
          </form>
      </div>
  </div>
</div>

<?php
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